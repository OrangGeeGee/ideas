<?php

use App\Idea;
use App\Comment;
use App\Activity;
use App\View;
use App\Vote;
use App\Setting;
use App\Share;

require 'UserAgentParser.php';

class Activities {
  const OPEN_APP = 'Opened application';
  const ADD_IDEA = 'Added new idea: "%s"';
  const OPEN_IDEA = 'Opened idea: "%s"';
  const DELETE_IDEA = 'Deleted idea: "%s"';
  const VOTE_IDEA = 'Voted for idea: "%s"';
  const SHARE_IDEA = 'Shared idea %s with %s';
  const UNVOTE_IDEA = 'Removed vote from idea: "%s"';
  const ADD_COMMENT = 'Added new comment under "%s": "%s"';

  const SETTING_NOTIFICATION_VOTE = '%s %s vote notifications';
  const SETTING_NOTIFICATION_COMMENT = '%s %s comment notifications';
  const SETTING_NOTIFICATION_NEWSLETTER = '%s %s daily newsletter';


  /**
   * @param string $description
   * @param string $param1
   * @param string $param2
   */
  public static function record($description, $param1 = '', $param2 = '') {
    $data = [
      'user_id' => \Auth::user()->id,
      'description' => sprintf($description, $param1, $param2),
      'timestamp' => DB::raw('NOW()'),
    ];

    try {
      $userAgentData = parse_user_agent();
      $data = array_merge($data, $userAgentData);
    } catch(Exception $e) {}

    Activity::create($data);
  }
}

Idea::created(function($idea) {
  Activities::record(Activities::ADD_IDEA, $idea->title);
});

Idea::deleted(function($idea) {
  Activities::record(Activities::DELETE_IDEA, $idea->title);
});

Comment::created(function($comment) {
  Activities::record(Activities::ADD_COMMENT, $comment->idea->title, $comment->text);
});

View::created(function($view) {
  Activities::record(Activities::OPEN_IDEA, $view->idea->title);
});

Vote::created(function($vote) {
  Activities::record(Activities::VOTE_IDEA, $vote->idea->title);
});

Setting::updated(function($setting) {
  $notifications = [
    'receiveVoteNotification' => Activities::SETTING_NOTIFICATION_VOTE,
    'receiveCommentNotification' => Activities::SETTING_NOTIFICATION_COMMENT,
    'receiveDailyNewsletter' => Activities::SETTING_NOTIFICATION_NEWSLETTER,
  ];

  foreach ( $notifications as $notificationType => $messageTemplate ) {

    if ( !$setting->isDirty($notificationType) ) {
      continue;
    }

    $state = ( $setting->$notificationType == true ) ? 'enabled' : 'disabled';
    Activities::record($messageTemplate, $setting->user->name, $state);
  }
});

Share::created(function($share) {
  Activities::record(Activities::SHARE_IDEA, $share->idea->title, $share->recipient->name);
});
