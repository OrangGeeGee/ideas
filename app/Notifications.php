<?php

use App\Idea;
use App\Comment;

class Notifications {


  /**
   * @param Idea $idea
   */
  public static function newIdea(Idea $idea) {
    App::setLocale($idea->user->getLocale());

    # Send the idea for validation
    if ( $idea->user->hasEstonianEmailAddress() ) {
      Mail::send('emails.ideaValidation', compact('idea'), function($message) use ($idea) {
        $message
          ->to('mattias.saldre@swedbank.ee', 'Mattias Saldre')
          ->subject(self::prefixSubject(trans('emails.newIdea')));
      });
    }
  }


  /**
   * Notifies secretaries about a new idea. Candy time!
   *
   * @param Idea $idea
   */
  public static function notifySecretaries(Idea $idea) {
    Mail::send('emails.notificationToSecretaries', compact('idea'), function($message) use ($idea) {
      $message
        ->to('liivalaia-sekretarid@swedbank.ee', 'Liivalaia sekretärid')
        ->cc('mattias.saldre@swedbank.ee', 'Mattias Saldre')
        ->subject(self::prefixSubject(trans('emails.newIdea')));
    });
  }


  /**
   * @param Comment $comment
   */
  public static function newComment(Comment $comment) {
    $ideaAuthor = $comment->idea->user;
    App::setLocale($ideaAuthor->getLocale());

    # Notify the author of the idea.
    if ( $comment->user->id != $ideaAuthor->id ) {
      Mail::send('emails.comment', compact('comment'), function($message) use($ideaAuthor) {
        $message
          ->to($ideaAuthor->email, $ideaAuthor->name)
          ->subject(self::prefixSubject(trans('emails.newComment')));
      });
    }
  }


  /**
   * @param Idea $idea
   */
  public static function newVote(Idea $idea) {
    $ideaAuthor = $idea->user;
    App::setLocale($ideaAuthor->getLocale());

    Mail::send('emails.vote', compact('idea'), function ($message) use ($ideaAuthor) {
      $message
        ->to($ideaAuthor->email, $ideaAuthor->name)
        ->subject(self::prefixSubject(trans('emails.newVote')));
    });
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

    $data['ideas'] = \App\Idea::latest($periodStart, $periodEnd)->get();
    $data['comments'] = \App\Comment::latest($periodStart, $periodEnd)->get();
    $data['votes'] = \App\Vote::latest($periodStart, $periodEnd)->get();

    # No updates, cancel notification.
    if ( $data['ideas']->count() == 0
      && $data['comments']->count() == 0
      && $data['votes']->count() == 0 ) {
      return;
    }

    # TODO: Implement proper subscription for this.
    foreach ( \App\WHOISUser::where(['name' => 'Mattias Saldre'])->get() as $subscriber ) {
      \App::setLocale($subscriber->getLocale());
      $data['title'] = trans('emails.dailyHeading') . " " . $yesterday->format('d/m/Y');

      \Mail::send('emails.daily', $data, function($message) use ($subscriber, $data) {
        $message
          ->to($subscriber->email)
          ->subject(self::prefixSubject($data['title']));
      });
    }
  }


  /**
   * @param string $title
   * @return string
   */
  private static function prefixSubject($title) {
    $appName = ( App::getLocale() == 'et' ) ? 'Ideekeskkond' : 'Brainstorm';

    return "[$appName] $title";
  }
}

Idea::created(function($idea) {
  Notifications::newIdea($idea);
});

Comment::created(function($comment) {
  Notifications::newComment($comment);
});
