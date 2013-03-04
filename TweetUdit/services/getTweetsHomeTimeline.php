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

$id = $_SESSION['user'];

$con = mysqli_connect($host,$username,$password,$dbName);
if(mysqli_connect_errno($con)) {
    header('Location: ../DBError.html');
}

$rs = mysqli_query($con,"select * from user where id='".$id."'");

if($rs =  mysqli_fetch_array($rs)) {
    $user = new User($rs);
} else {
    header('Location: ../DBError.html');
}


/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$tweets = $connection->get("statuses/home_timeline",array("count"=>10,"exclude_replies"=>true,"include_entities"=>false));

mysqli_query($con, "delete from tweet where user_id = '".$user->get_id()."'");

foreach ($tweets as $tweet) {
    $user->set_tweets(new Tweet($tweet->id_str, $tweet->text, $tweet->user->screen_name, $tweet->user->profile_image_url, $tweet->created_at));
    mysqli_query($con, "insert into tweet values (
                            '".$tweet->id_str."',
                            '".addslashes($tweet->text)."',
                            '".$tweet->user->screen_name."',
                            '".$tweet->user->profile_image_url."',
                            '".$tweet->created_at."',
                            '".$user->get_id()."')") or die(mysqli_error($con));
}

mysqli_close($con);
$_SESSION['user']=  $user->get_id();
echo $user->get_tweets_json();
?>
