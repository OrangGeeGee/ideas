<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Share extends Model {
  protected $fillable = [
    'idea_id',
    'user_id',
    'recipient_id',
  ];
  public $timestamps = false;

  public static function boot() {
    self::creating(function($share) {
      $share->user_id = \Auth::user()->id;
      $share->timestamp = Carbon::now();
    });
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }

  public function recipient() {
    return $this->belongsTo('App\WHOISUser');
  }
}