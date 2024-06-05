<?php

//https://www.php.net/manual/en/function.rand.php
class passwordGenerator
{
  public function __construct($length)
  {
    $this->newPassWord($length);
  }
  function newPassWord($length)
  {
    $str = random_bytes($length);
    $str = base64_encode($str);
    return strval($str);
  }
}
?>
