<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Main
 *
 * @author Hemanth
 */
class Main {
    //put your code here
    
    var $app_id;
    var $app_url;
    var $app_name;
    var $app_secret;
    var $fan_id;
    var $fan_url;
    
    
public function getApp_id() {
return $this->app_id;
}
function __construct($app_id) {
    $this->app_id = $app_id;
}

public function setApp_id($app_id) {
$this->app_id = $app_id;
}

public function getApp_url() {
return $this->app_url;
}

public function setApp_url($app_url) {
$this->app_url = $app_url;
}

public function getApp_name() {
return $this->app_name;
}

public function setApp_name($app_name) {
$this->app_name = $app_name;
}

public function getApp_secret() {
return $this->app_secret;
}

public function setApp_secret($app_secret) {
$this->app_secret = $app_secret;
}

public function getFan_id() {
return $this->fan_id;
}

public function setFan_id($fan_id) {
$this->fan_id = $fan_id;
}

public function getFan_url() {
return $this->fan_url;
}

public function setFan_url($fan_url) {
$this->fan_url = $fan_url;
}


}
?>
