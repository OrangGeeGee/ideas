<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
  protected $fillable = ['user_id'];
  protected $hidden = ['user_id'];
  public $timestamps = false;
}
