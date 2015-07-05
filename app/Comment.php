<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
  protected $fillable = array('idea_id', 'text');

  public function scopeLatest($query, $timestamp) {
    return $query->where('updated_at', '>=', $timestamp);
  }

  public function idea() {
    return $this->belongsTo('App\Idea');
  }
}
