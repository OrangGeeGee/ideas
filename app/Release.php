<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Release extends Model {
  public $timestamps = false;

  public function getNotes()
  {
    $locale = \App::getLocale();
    $property = "notes_{$locale}";

    return $this->$property;
  }
}