<?php

class Comment extends Eloquent {
  protected $fillable = array('idea_id', 'text');

  /**
   * @param Builder $query
   * @param string $timestamp
   * @return Collection
   */
  public function scopeLatest($query, $timestamp)
  {
    return $query->where('updated_at', '>=', $timestamp);
  }
}
