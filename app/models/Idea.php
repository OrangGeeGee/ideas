<?php

class Idea extends Eloquent {
  protected $fillable = array('category_id', 'title', 'description');

  /**
   * @param Builder $query
   * @param string $timestamp
   * @return Collection
   */
  public function scopeLatest($query, $timestamp = '')
  {
    return $query->where('updated_at', '>=', $timestamp);
  }

  public function userData()
  {
    $pivotFields = array('voted_at', 'seen_at');
    return $this->belongsToMany('User')->withPivot($pivotFields);
  }

  public function hasBeenVotedFor()
  {
    return !!$this->userData()
      ->where('user_id', $this->id)
      ->where('voted_at', '!=', '0000-00-00 00:00:00')
      ->first();
  }

  public function user()
  {
    return $this->belongsTo('User');
  }
}
