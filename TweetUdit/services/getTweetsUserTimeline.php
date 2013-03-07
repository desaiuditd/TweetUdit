<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
require_once('../twitteroauth/twitteroauth.php');
require_once('../twitteroauth/config.php');
require_once ('../DBConfig.php');
require_once ('../User.php');
require_once ('../Tweet.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ../clearsession.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

if(empty($_REQUEST['screen_name'])) {
    header('Location: ../error.html');
}

$screen_name = $_REQUEST['screen_name'];

echo $screen_name;
?>