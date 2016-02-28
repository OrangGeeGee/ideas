<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
  public $timestamps = false;
  protected $fillable = [
    'user_id',
    'description',
    'referer',
    'platform',
    'browser',
    'version',
    'timestamp',
  ];
}