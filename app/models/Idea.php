<?php

class Idea extends Eloquent {
  protected $fillable = array('title', 'description');

  /**
   * @param Builder $query
   * @param string $timestamp
   * @return Collection
   */
  public function scopeLatest($query, $timestamp = '')
  {
    return $query->where('updated_at', '>=', $timestamp);
  }
}
