<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract {
	use Authenticatable;

	public $incrementing = false;
	protected $fillable = [
		'id',
		'last_activity_at',
	];
	protected $hidden = [
		'password',
		'remember_token',
	];
	public $timestamps = false;

	public static function boot() {
		parent::boot();

		self::created(function($user) {
			$user->settings()->create([]);
		});
	}

	public function ideas() {
		return $this->hasMany('App\Idea');
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

	public function settings() {
		return $this->hasOne('App\Setting');
	}

	public function hasEstonianEmailAddress() {
		return WHOISUser::find($this->id)->hasEstonianEmailAddress();
	}

	public function getFirstName() {
		return explode(' ', WHOISUser::find($this->id)->name)[0];
	}

	public function updateActivityTimestamp() {
		$this->last_activity_at = \DB::raw('NOW()');
		$this->save();
	}

}
