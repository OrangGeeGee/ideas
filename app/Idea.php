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

  public function votes() {
    return $this->hasMany('App\Vote');
  }

  public function views() {
    return $this->belongsToMany('App\User');
  }

  public function comments() {
    return $this->hasMany('App\Comment');
  }

  public function hasBeenVotedFor() {
    return $this->votes()->where('user_id', \Auth::user()->id)->count() > 0;
  }

  public function user() {
    return $this->belongsTo('App\WHOISUser');
  }

  public function status() {
    return $this->belongsTo('App\Status');
  }
}
