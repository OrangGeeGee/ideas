<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CommentLike extends Model {
  public $timestamps = false;

  public static function boot() {
    self::creating(function($like) {
      $like->user_id = Auth::user()->id;
      $like->timestamp = Carbon::now();
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
}