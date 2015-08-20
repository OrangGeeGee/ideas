<?php

require_once 'Email.php';

use App\Vote;
use App\Idea;
use App\Comment;
use App\WHOISUser;

class Notifications {


  /**
   * @param Idea $idea
   */
  public static function newIdea(Idea $idea) {
    $moderator = WHOISUser::find('msald');

    (new Email)
      ->subject(trans('emails.newIdea'))
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
      ->subject(trans('emails.newIdea'))
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
      ->subject(trans('emails.newComment'))
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
      ->subject(trans('emails.newVote'))
      ->to($ideaAuthor)
      ->view('emails.vote', compact('vote'))
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

    $data['title'] = trans('emails.dailyHeading') . " " . $yesterday->format('d/m/Y');
    $data['ideas'] = \App\Idea::latest($periodStart, $periodEnd)->get();
    $data['comments'] = \App\Comment::latest($periodStart, $periodEnd)->get();
    $data['votes'] = \App\Vote::latest($periodStart, $periodEnd)->get();

    # No updates, cancel notification.
    if ( $data['ideas']->count() == 0
      && $data['comments']->count() == 0
      && $data['votes']->count() == 0 ) {
      return;
    }

    foreach ( \App\Setting::where('receiveDailyNewsletter', true)->get() as $setting ) {
      # TODO: A separate query for every single user can hinder performance.
      $subscriber = \App\WHOISUser::find($setting->user_id);

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
