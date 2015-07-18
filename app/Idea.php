<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model {
  protected $fillable = array('category_id', 'title', 'description');
  protected $softDelete = true;

  /**
   * @param Builder $query
   * @param string $timestamp
   * @return Collection
   */
  public function scopeLatest($query, $timestamp = '') {
    return $query->where('updated_at', '>=', $timestamp);
  }

  public function userData() {
    $pivotFields = [
      'voted_at',
      'seen_at',
    ];

    return $this->belongsToMany('App\User')->withPivot($pivotFields);
  }

  public function votes() {
    return $this->belongsToMany('App\User')->where('voted_at', '>', '0000-00-00 00:00:00');
  }

  public function comments() {
    return $this->hasMany('App\Comment');
  }

  public function hasBeenVotedFor() {
    return !!$this->userData()
      ->where('user_id', $this->id)
      ->where('voted_at', '!=', '0000-00-00 00:00:00')
      ->first();
  }

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }

  public function status() {
    return $this->belongsTo('App\Status');
  }
}
