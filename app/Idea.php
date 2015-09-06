<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Idea extends Model {
  use SoftDeletes;
  protected $fillable = array('category_id', 'title', 'description');
  protected $dates = [
    'deleted_at',
  ];

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

  public function votes() {
    return $this->hasMany('App\Vote');
  }

  public function shares() {
    return $this->hasMany('App\Share');
  }

  public function views() {
    return $this->hasMany('App\View');
  }

  public function view() {
    return $this->views()->create([]);
  }

  public function comments() {
    return $this->hasMany('App\Comment');
  }

  public function subscriptions() {
    return $this->hasMany('App\IdeaSubscription');
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

  public function statusChanges() {
    return $this->hasMany('App\StatusChange');
  }

  public function hasStatus($statusId) {
    $lastStatusChange = $this->statusChanges->last();
    $currentStatusId = $lastStatusChange ? $lastStatusChange->status_id : 0;

    return $currentStatusId == $statusId;
  }

  public function statusCanBeChangedBy(User $user) {
    return $this->user_id == $user->id || $user->settings->canModerateStatuses;
  }

  public function generateURL() {
    return env('APP_URL') . "#ideas/$this->id";
  }


  /**
   * @return IdeaSubscription
   */
  public function subscribe() {
    return $this->subscriptions()->create([
      'user_id' => Auth::user()->id,
    ]);
  }


  /**
   * @return IdeaSubscription
   */
  public function getUserSubscription() {
    return $this->subscriptions()->where('user_id', Auth::user()->id)->first();
  }


  /**
   * @return bool
   */
  public function userHasSubscribed() {
    return !!$this->getUserSubscription();
  }
}
