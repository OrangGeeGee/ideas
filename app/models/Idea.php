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

  public function votes()
  {
    return $this->belongsToMany('User')->withTimestamps();
  }

  public function user()
  {
    return $this->belongsTo('User');
  }
}
