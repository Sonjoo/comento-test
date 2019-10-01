<?php
namespace App\Dtos;

class CouponDTO {
  public $prefix;

  public $group_name;

  public $length;

  public function __construct($prefix, $group_name, $length = 16) {
    $this->prefix = $prefix;
    $this->group_name = $group_name;
    $this->length = $length;
  }
}
