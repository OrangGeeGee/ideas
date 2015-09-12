<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Image extends Model {
  protected $fillable = [
    'id',
  ];
  protected $dates = [
    'uploaded_at',
  ];
  protected $hidden = [
    'pivot',
  ];
  public $incrementing = false;
  public $timestamps = false;

  public static function boot() {
    self::creating(function($image) {
      $image->uploaded_at = Carbon::now();
    });
  }
}