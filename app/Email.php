<?php

use App\WHOISUser;

class Email {
  private $toEmail;
  private $toName;
  private $ccEmail;
  private $ccName;
  private $subject;
  private $view;
  private $data;

  /**
   * @param WHOISUser|String $recipient
   * @param string [$name]
   * @return $this
   */
  public function to($recipient, $name = '') {

    if ( $recipient instanceof WHOISUser ) {
      $this->toEmail = $recipient->email;
      $this->toName = $recipient->name;
    }
    else {
      $this->toEmail = $recipient;
      $this->toName = $name;
    }

    return $this;
  }

  /**
   * @param WHOISUser|string $recipient
   * @param string [$name]
   * @return $this
   */
  public function cc($recipient, $name = '') {

    if ( $recipient instanceof WHOISUser ) {
      $this->ccEmail = $recipient->email;
      $this->ccName = $recipient->name;
    }
    else {
      $this->ccEmail = $recipient;
      $this->ccName = $name;
    }

    return $this;
  }

  /**
   * @param string $subject
   * @return $this
   */
  public function subject($subject) {
    $this->subject = $subject;
    return $this;
  }

  /**
   * @param string $view
   * @param array $data
   * @return $this
   */
  public function view($view, $data) {
    $this->view = $view;
    $this->data = $data;

    return $this;
  }

  /**
   *
   */
  public function send() {
    $this->data['locale'] = $this->getRecipientLocale();

    if ( !isset($this->data['title']) ) {
      $this->data['title'] = localize('app.name', $this->data['locale']);
    }

    \Mail::send($this->view, $this->data, function($message) {
      $message->subject($this->subject);
      $message->to($this->toEmail, $this->toName);
      $message->from('brainstorm@eos.crebit.ee', trans('app.name'));

      if ( $this->ccEmail ) {
        $message->cc($this->ccEmail, $this->ccName);
      }
    });
  }

  /**
   * @return string
   */
  private function getRecipientLocale() {
    return ( substr($this->toEmail, -3) == '.ee' ) ? 'et' : 'en';
  }

  /**
   * @param \App\Idea $idea
   * @return string
   */
  public static function generateIdeaLink(App\Idea $idea) {
    return "<a href=\"{$idea->generateURL()}\" target=\"_blank\">{$idea->title}</a>";
  }
}
