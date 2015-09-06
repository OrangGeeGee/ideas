<?php

require_once 'Email.php';

use App\Vote;
use App\Idea;
use App\Comment;
use App\CommentLike;
use App\Share;
use App\WHOISUser;
use Illuminate\Support\Facades\DB;

class Notifications {


  /**
   * @param Idea $idea
   */
  public static function newIdea(Idea $idea) {
    $moderator = WHOISUser::find('msald');

    (new Email)
      ->subject(localize('emails.newIdea', 'et'))
      ->to($moderator)
      ->view('emails.ideaValidation', compact('idea'))
      ->send();
  }


  /**
   * Notifies secretaries about a new idea. Candy time!
   *
   * @param Idea $idea
   */
  public static function notifySecretaries(Idea $idea) {
    $moderator = WHOISUser::find('msald');

    (new Email)
      ->subject(localize('emails.newIdea', 'et'))
      ->to('liivalaia-sekretarid@swedbank.ee', 'Liivalaia sekretärid')
      ->cc($moderator)
      ->view('emails.notificationToSecretaries', compact('idea'))
      ->send();
  }


  /**
   * Notify the author of the idea.
   *
   * @param Comment $comment
   */
  public static function newComment(Comment $comment) {
    $ideaAuthor = $comment->idea->user;

    foreach ( $comment->idea->subscriptions as $subscription ) {

      if ( $subscription->user_id == $comment->user->id ) {
        continue;
      }

      (new Email)
        ->subject(localize('emails.newComment', $ideaAuthor->getLocale(), [
          'idea' => $comment->idea->title,
        ]))
        ->to($subscription->user)
        ->view('emails.comment', compact('comment'))
        ->send();
    }

    # Don't notify the author if the new comment was made by him/her or if the user has disabled this notification.
    if ( $comment->user->id == $ideaAuthor->id || !$comment->user->settings->receiveCommentNotification ) {
      return;
    }

    (new Email)
      ->subject(localize('emails.newComment', $ideaAuthor->getLocale(), [
        'idea' => $comment->idea->title
      ]))
      ->to($ideaAuthor)
      ->view('emails.comment', compact('comment'))
      ->send();
  }


  /**
   * @param WHOISUser $user
   * @param Comment $comment
   */
  public static function mentionUserInComment(WHOISUser $user, Comment $comment) {
    (new Email)
      ->subject(localize('emails.mentionedInComment', $user->getLocale(), [
        'user' => $comment->user->name,
        'idea' => $comment->idea->generateURL(),
      ]))
      ->to($user)
      ->view('emails.mentionedInComment', compact('comment'))
      ->send();
  }


  /**
   * Notify the author of the comment.
   *
   * @param CommentLike $comment
   */
  public static function likeComment(CommentLike $like) {
    $commentAuthor = $like->comment->user;

    (new Email)
      ->subject(localize('emails.newCommentLike', $commentAuthor->getLocale(), [
        'user' => $like->user->name,
      ]))
      ->to($commentAuthor)
      ->view('emails.commentLike', compact('like'))
      ->send();
  }


  /**
   * @param Vote $vote
   */
  public static function newVote(Vote $vote) {
    $ideaAuthor = $vote->idea->user;

    (new Email)
      ->subject(localize('emails.newVote', $ideaAuthor->getLocale()))
      ->to($ideaAuthor)
      ->view('emails.vote', compact('vote'))
      ->send();
  }


  /**
   * @param Idea $idea
   * @param WHOISUser $sharer
   * @param WHOISUser $recipient
   */
  public static function shareIdea(Idea $idea, WHOISUser $sharer, WHOISUser $recipient) {
    (new Email)
      ->subject(localize('emails.sharingTitle', $recipient->getLocale(), [
        'sharer' => $sharer->name,
        'idea' => $idea->title,
      ]))
      ->to($recipient)
      ->view('emails.shareIdea', compact('idea', 'sharer'))
      ->send();
  }


  /**
   * Send an email with latest ideas, comments, etc to subscribed users.
   */
  public static function dailyUpdate() {
    $data = [];
    $today = new DateTime();
    $yesterday = (new DateTime())->modify('-1 day');
    $periodStart = $yesterday->format('Y-m-d');
    $periodEnd = $today->format('Y-m-d');

    $data['periodStart'] = $periodStart;
    $data['ideas'] = Idea::latest($periodStart, $periodEnd)->with('comments.user', 'votes')->get();

    # No updates, cancel notification.
    if ( $data['ideas']->count() == 0 ) {
      return;
    }

    foreach ( \App\Setting::where('receiveDailyNewsletter', true)->get() as $setting ) {
      # TODO: A separate query for every single user can hinder performance.
      $subscriber = \App\WHOISUser::find($setting->user_id);
      $data['title'] = localize('emails.dailyHeading', $subscriber->getLocale()) . " " . $yesterday->format('d/m/Y');

      (new Email)
        ->subject($data['title'])
        ->to($subscriber)
        ->view('emails.daily', $data)
        ->send();
    }
  }
}

Idea::created(function($idea) {
  Notifications::newIdea($idea);
});

Comment::created(function($comment) {
  $author = $comment->idea->user;
  $notifyAboutComment = $author->settings->receiveCommentNotification;

  foreach ( getMentionedUsers($comment->text) as $mentionedName ) {
    $mentionedUser = WHOISUser::where(DB::raw('replace(name, " ", "")'), $mentionedName)->first();

    # No need to send a double notification to the mentioned user (one
    # for being mentioned and one for having a new comment on the idea).
    if ( $mentionedUser->id == $author->id && $notifyAboutComment || !$mentionedUser->settings->receiveMentionNotification ) {
      continue;
    }

    Notifications::mentionUserInComment($mentionedUser, $comment);
  }

  Notifications::newComment($comment);
});

CommentLike::created(function($like) {

  # Check if the user doesn't want to receive notifications.
  if ( !$like->comment->user->settings->receiveCommentLikeNotification ) {
    return;
  }

  Notifications::likeComment($like);
});

Vote::created(function($vote) {

  # Check if the user doesn't want to receive notifications.
  if ( !$vote->idea->user->settings->receiveVoteNotification ) {
    return;
  }

  Notifications::newVote($vote);
});

Share::created(function($share) {
  Notifications::shareIdea($share->idea, $share->user, $share->recipient);
});
