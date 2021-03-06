<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($bitcoin->can_connect() === true){
    $dBalance = $bitcoin->query('getbalance');
  } else {
    $dBalance = 0;
    $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
  }
  // Fetch locked balance from transactions
  $dLockedBalance = $transaction->getLockedBalance();
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("BALANCE", $dBalance);
$smarty->assign("LOCKED", $dLockedBalance);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
