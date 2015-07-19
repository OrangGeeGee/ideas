<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model {
  protected $fillable = array('category_id', 'title', 'description');
  protected $softDelete = true;

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

  public function generateURL() {
    return env('APP_URL') . "#ideas/$this->id";
  }
}
