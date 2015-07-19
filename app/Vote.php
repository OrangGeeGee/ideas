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
   * @param string $timestamp
   * @return Collection
   */
  public function scopeLatest($query, $timestamp = '') {
    return $query->where('timestamp', '>=', $timestamp);
  }
}