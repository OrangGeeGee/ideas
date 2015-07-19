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
    $query->where('timestamp', '>=', $timestampStart);

    if ( $timestampEnd ) {
      $query->where('timestamp', '<=', $timestampEnd);
    }

    return $query;
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }
}