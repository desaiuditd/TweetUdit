<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once('../twitteroauth/twitteroauth.php');
require_once('../twitteroauth/config.php');
require_once ('../User.php');
require_once ('../Tweet.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ../clearsession.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];
$user = unserialize($_SESSION['user']);

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$tweets = $connection->get("statuses/home_timeline",array("count"=>10,"exclude_replies"=>true,"include_entities"=>false));

foreach ($tweets as $tweet) {
    $user->set_tweets(new Tweet($tweet->id_str, $tweet->text, $tweet->user->screen_name, $tweet->user->profile_image_url, $tweet->created_at));
}
$_SESSION['user']=  serialize($user);
echo $user->get_tweets_json();
?>
