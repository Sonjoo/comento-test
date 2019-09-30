<?php
namespace App;

class Code extends Authenticatable
{
    protected $guarded = [];

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public $incrementing = false;

    public function users() {
      $this->hasMany('App\User', 'code', 'code');
    }
}
