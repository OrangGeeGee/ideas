<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {
  protected $fillable = [
    'idea_id',
    'user_id',
    'timestamp',
  ];
  public $timestamps = false;

  /**
   * @param Builder $query
   * @param string $timestampStart
   * @param string $timestampEnd
   * @return Collection
   */
  public function scopeLatest($query, $timestampStart = '', $timestampEnd = null) {
    return $query->whereBetween('timestamp', [
      $timestampStart,
      isset($timestampEnd) ? $timestampEnd : \DB::raw('now')
    ]);
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }
}