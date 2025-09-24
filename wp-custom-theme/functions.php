<?php

require_once 'vendor/autoload.php'; // for uses PHP libs via composer

if ( ! ( new Start\PreventFatalError() )->is_success() ) {
  return;
}

