<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusChange extends Model {
  protected $fillable = [
    'idea_id',
    'comment_id',
    'status_id',
  ];
  public $timestamps = false;

  public static function boot() {
    parent::boot();

    self::creating(function($statusChange) {
      $statusChange->timestamp = \DB::raw('NOW()');
    });
  }

  /**
   * @param Builder $query
   * @param string $timestampStart
   * @param string $timestampEnd
   * @return Collection
   */
  public function scopeLatest($query, $timestampStart = '', $timestampEnd = null) {
    $query->where('timestamp', '>=', $timestampStart);

    if ( $timestampEnd ) {
      $query->where('timestamp', '<=', $timestampEnd);
    }

    return $query;
  }

  public function comment() {
    return $this->belongsTo('App\Comment');
  }
}