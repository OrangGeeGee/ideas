<?php

require_once 'Email.php';

use App\Vote;
use App\Idea;
use App\Comment;
use App\Share;
use App\WHOISUser;

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

    # Don't notify the author if the new comment was made by him/her.
    if ( $comment->user->id == $ideaAuthor->id ) {
      return;
    }

    (new Email)
      ->subject(localize('emails.newComment', $ideaAuthor->getLocale()))
      ->to($ideaAuthor)
      ->view('emails.comment', compact('comment'))
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

  # Check if the user doesn't want to receive notifications.
  if ( !$comment->idea->user->settings->receiveCommentNotification ) {
    return;
  }

  Notifications::newComment($comment);
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
