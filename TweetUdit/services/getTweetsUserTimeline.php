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

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$con = mysqli_connect($host,$username,$password,$dbName);
if(mysqli_connect_errno($con)) {
    header('Location: ../DBError.html');
}

if(empty($_REQUEST['screen_name'])) {
    header('Location: ../error.html');
}

if(stripos($_REQUEST['screen_name'],"@")===0) {
    $screen_name = strtok($_REQUEST['screen_name'],"@");
    $rs = mysqli_query($con,"select * from user where screen_name='".$screen_name."'");
} else {
    $rs = mysqli_query($con,"select * from user where name='".$_REQUEST['screen_name']."'");
    if($rs = mysqli_fetch_array($rs)) {
        $screen_name = $rs['screen_name'];
        $rs = mysqli_query($con,"select * from user where screen_name='".$screen_name."'");
    } else {
        header('Location: ../DBError.html');
    }
}

if($rs =  mysqli_fetch_array($rs)) {
    $user = new User($rs);
    mysqli_query($con, "update user
                            set id = '".$user->get_id()."',
                            screen_name = '".$user->get_screen_name()."',
                            name = '".$user->get_name()."',
                            profile_image_url = '".$user->get_profile_image_url()."',
                            profile_background_image_url = '".$user->get_profile_background_image_url()."',
                            profile_sidebar_fill_color = '".$user->get_profile_sidebar_fill_color()."',
                            profile_background_color = '".$user->get_profile_background_color()."',
                            profile_link_color = '".$user->get_profile_link_color()."'
                        where id = '".$user->get_id()."'");
} else {
    $user = new User($connection->get("users/show",array("screen_name"=>$screen_name,"includes_entities"=>false)));
    mysqli_query($con, "insert into user values (
                            '".$user->get_id()."',
                            '".$user->get_screen_name()."',
                            '".$user->get_name()."',
                            '".$user->get_profile_image_url()."',
                            '".$user->get_profile_background_image_url()."',
                            '".$user->get_profile_sidebar_fill_color()."',
                            '".$user->get_profile_background_color()."',
                            '".$user->get_profile_link_color()."')");
}

$tweets = $connection->get("statuses/user_timeline",array("screen_name"=>$screen_name,"count"=>10,"exclude_replies"=>true));

mysqli_query($con, "delete from tweet where user_id='' or user_id = '".$user->get_id()."'");

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

echo json_encode(array_slice($tweets, 0, 10));
?>