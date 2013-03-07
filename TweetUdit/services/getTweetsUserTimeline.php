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

$screen_name = strtok($_REQUEST['screen_name'],"@");

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$tweets = $connection->get("statuses/user_timeline",array("screen_name"=>$screen_name,"count"=>10,"exclude_replies"=>true));

echo json_encode($tweets);
?>