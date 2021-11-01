<?php
class User
{
  private $data = [];

  function __get($var_name)
  {
    return $this->data[$var_name];
  }
  function __set($var_name, $value)
  {
    $this->data[$var_name] = $value;
  }
}
?>