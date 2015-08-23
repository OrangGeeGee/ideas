<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class View extends Model {
  protected $hidden = [
    'idea',
  ];
  protected $dates = [
    'timestamp',
  ];
  public $timestamps = false;

  public static function boot() {
    parent::boot();

    self::creating(function($view) {
      $view->user_id = \Auth::user()->id;
      $view->timestamp = Carbon::now();
    });
  }

  /**
   * @param Builder $query
   * @param string $timestampStart
   * @param string $timestampEnd
   * @return Collection
   */
  public function scopeLatest(Builder $query, $timestampStart = '', $timestampEnd = null) {
    $query->where('timestamp', '>=', $timestampStart);

    if ( $timestampEnd ) {
      $query->where('timestamp', '<=', $timestampEnd);
    }

    return $query;
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }
}