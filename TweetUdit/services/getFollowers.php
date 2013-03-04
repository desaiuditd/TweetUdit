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
mysqli_close($con);

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$followers = $connection->get("followers/ids",array("user_id"=>$user->get_id(),"count"=>10,"stringify_ids"=>true));
$data = array();

foreach ($followers->ids as $follower) {

    $temp = new User($connection->get("users/show",array("user_id"=>$follower,"includes_enntities"=>false)));
    $data[] = $temp->jsonSerialize();
}

echo json_encode($data);
?>
