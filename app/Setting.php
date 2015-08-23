<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
  protected $fillable = [
    'landingPageVisited',
    'receiveCommentNotification',
    'receiveCommentLikeNotification',
    'receiveDailyNewsletter',
    'receiveVoteNotification',
  ];
  protected $hidden = [
    'user_id',
    'user',
  ];
  protected $primaryKey = 'user_id';
  public $timestamps = false;

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }
}
