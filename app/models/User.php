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
  /**
   * Get the token value for the "remember me" session.
   *
   * @return string
   */
  public function getRememberToken()
  {
    // TODO: Implement getRememberToken() method.
  }
  /**
   * Set the token value for the "remember me" session.
   *
   * @param  string $value
   * @return void
   */
  public function setRememberToken($value)
  {
    // TODO: Implement setRememberToken() method.
  }
  /**
   * Get the column name for the "remember me" token.
   *
   * @return string
   */
  public function getRememberTokenName()
  {
    // TODO: Implement getRememberTokenName() method.
  }
}