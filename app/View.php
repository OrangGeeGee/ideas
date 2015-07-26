<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model {
  public $timestamps = false;

  public static function boot() {
    parent::boot();

    self::creating(function($view) {
      $view->user_id = \Auth::user()->id;
      $view->timestamp = \DB::raw('NOW()');
    });
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }
}