<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IdeaSubscription extends Model {
  protected $fillable = [
    'user_id',
  ];
  public $timestamps = false;

  public static function boot() {
    self::creating(function($subscription) {
      $subscription->created_at = Carbon::now();
    });
  }

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }
}
