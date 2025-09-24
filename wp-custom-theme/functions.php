<?php

require_once 'vendor/autoload.php'; // for uses PHP libs via composer

if ( ! ( new Think\PreventFatalError() )->is_success() ) {
  return;
}

