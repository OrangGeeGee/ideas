<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract {
	use Authenticatable;

	public $incrementing = false;
	protected $fillable = [
		'id',
	];
	protected $hidden = [
		'password',
		'remember_token',
	];
	public $timestamps = false;

	public function ideas() {
		return $this->hasMany('App\Idea');
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

	public function hasFreeVotes() {
		#return $this->available_votes > 0;
		# TEMP: For the moment, users can vote for every idea there is.
		return true;
	}

	public function getFirstName() {
		return explode(' ', $this->name)[0];
	}

	public function hasEstonianEmailAddress() {
		$whois = WHOISUser::find($this->id);
		return substr($whois->email, -3) == '.ee';
	}

}
