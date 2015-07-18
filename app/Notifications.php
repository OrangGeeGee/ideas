<?php

use App\Idea;
use App\Comment;

class Notifications {


  /**
   * @param Idea $idea
   */
  public static function newIdea(Idea $idea) {
    App::setLocale($idea->user->getLocale());

    # Notify secretaries about the new idea. Candy time!
    if ( $idea->user->hasEstonianEmailAddress() ) {
      Mail::send('emails.idea', compact('idea'), function($message) use ($idea) {
        $message
          ->to('liivalaia-sekretarid@swedbank.ee', 'Liivalaia sekretärid')
          ->cc('mattias.saldre@swedbank.ee', 'Mattias Saldre')
          ->subject(self::prefixSubject(trans('emails.newIdea'), $idea->user->getLocale()));
      });
    }
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
