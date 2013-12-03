<?php

use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {
  protected $fillable = array('id', 'name', 'email');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

  public function ideas()
  {
    return $this->hasMany('Idea');
  }

  public function comments()
  {
    return $this->hasMany('Comment');
  }

}