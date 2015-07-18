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

	public function hasEstonianEmailAddress() {
		return WHOISUser::find($this->id)->hasEstonianEmailAddress();
	}

}
