<?php

class Activity extends Eloquent {
  public $timestamps = false;
  protected $fillable = [
    'user_id',
    'description',
    'platform',
    'browser',
    'version',
    'timestamp',
  ];
}