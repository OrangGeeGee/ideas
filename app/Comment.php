<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
  protected $fillable = array('idea_id', 'text');

  /**
   * @param Builder $query
   * @param string $timestampStart
   * @param string $timestampEnd
   * @return Collection
   */
  public function scopeLatest($query, $timestampStart = '', $timestampEnd = null) {
    return $query->whereBetween('updated_at', [
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
