<?php

$creds=array();

/**
 * passwords are stored using salt and sha256 hash encryption
 * the credential is [salt]>{ws@cs-}+{password}
 * eg for stavros is hash sha256 of the string "ws@cs-pitoglou"
 */
$creds['stavros']='fc7351bd1ab84b1dac1f5460583fee7647101095e201818cd8ba33f2c32b75bc';