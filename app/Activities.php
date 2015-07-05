<?php

use App\Idea;
use App\Comment;
use App\Activity;

require 'UserAgentParser.php';

class Activities {
  const OPEN_APP = 'Opened application';
  const ADD_IDEA = 'Added new idea: "%s"';
  const OPEN_IDEA = 'Opened idea: "%s"';
  const DELETE_IDEA = 'Deleted idea: "%s"';
  const VOTE_IDEA = 'Voted for idea: "%s"';
  const UNVOTE_IDEA = 'Removed vote from idea: "%s"';
  const ADD_COMMENT = 'Added new comment under "%s": "%s"';


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
