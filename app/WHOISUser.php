<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

class WHOISUser extends Model implements AuthenticatableContract {
	use Authenticatable;

	protected $connection = 'whois';
	protected $table = 'users';
	protected $fillable = [
		'id',
		'name',
		'email',
		'title',
	];
	public $incrementing = false;

	public static function boot() {
		parent::boot();

		self::creating(function($user) {
			$user->profileImageURL = $user->getIntranetProfileImageURL();
		});
	}

	public function getFirstName() {
		return explode(' ', $this->name)[0];
	}

	public function hasEstonianEmailAddress() {
		return substr($this->email, -3) == '.ee';
	}

	public function getLocale() {
		return $this->hasEstonianEmailAddress() ? 'et' : 'en';
	}

	public function getIntranetProfileImageURL() {

		# Without email we can't verify in which country the user is working.
		if ( !$this->email ) {
			return '';
		}

		return ( substr($this->email, -3) == '.se' || substr($this->email, -4) == '.com' )
			? "http://se.swedbank.net/idc/images/personalkatalog/{$this->id}.jpg"
			: "https://workspaces.swedbank.net/project/IDpicture/intranet/{$this->id}.jpg";
	}

	public function scopeNewest($query, $timestamp = '') {
		return $query->where('created_at', '>=', $timestamp);
	}

	public function settings() {
		return User::find($this->id)->settings();
	}

}
