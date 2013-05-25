<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
require_once('../lib/twitteroauth/twitteroauth.php');
require_once('../TwitterConfig.php');
require_once ('../DBConfig.php');
require_once ('../User.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ../clearsession.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

if(empty($_REQUEST['query'])) {
    header('Location: ../error.html');
}
$query = $_REQUEST['query'];

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
$followers = $connection->get("followers/list",array("user_id"=>$user->get_id(),"skip_status "=>true,"include_user_entities ",false));

$data = array();

foreach ($followers->users as $follower) {

    $rs = mysqli_query($con,"select id from user where id='".$follower->id_str."'");

    if($rs =  mysqli_fetch_array($rs)) {

        mysqli_query($con, "update user
                                set id = '".$follower->id_str."',
                                screen_name = '".$follower->screen_name."',
                                name = '".$follower->name."',
                                profile_image_url = '".$follower->profile_image_url."',
                                profile_background_image_url = '".$follower->profile_background_image_url."',
                                profile_sidebar_fill_color = '".$follower->profile_sidebar_fill_color."',
                                profile_background_color = '".$follower->profile_background_color."',
                                profile_link_color = '".$follower->profile_link_color."'
                            where id = '".$follower->id_str."'");
    } else {

        mysqli_query($con, "insert into user values (
                                '".$follower->id_str."',
                                '".$follower->screen_name."',
                                '".$follower->name."',
                                '".$follower->profile_image_url."',
                                '".$follower->profile_background_image_url."',
                                '".$follower->profile_sidebar_fill_color."',
                                '".$follower->profile_background_color."',
                                '".$follower->profile_link_color."')");
    }

    if(stristr($follower->screen_name,$query)!=false) {
        $data[] = "@".$follower->screen_name;
    }
    if(stristr(strtolower($follower->name),strtolower($query))!=false) {
        $data[] = $follower->name;
    }
}
sort($data);
echo json_encode($data);
?>
