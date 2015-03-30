<?php

class RequestLog extends Eloquent {
  protected $fillable = array('user_id', 'browser', 'version');

  public function setUpdatedAtAttribute() {
    // 'updated_at' column is not needed.
  }
}
