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
    $query->where('updated_at', '>=', $timestampStart);

    if ( $timestampEnd ) {
      $query->where('updated_at', '<=', $timestampEnd);
    }

    return $query;
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }

  public function likes() {
    return $this->hasMany('App\CommentLike');
  }

  public function like() {
    return $this->likes()->create([]);
  }
}
