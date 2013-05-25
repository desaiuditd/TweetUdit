<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once ('../User.php');
require_once ('../DBConfig.php');

if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ../clearsession.php');
}

$con = mysqli_connect($host,$username,$password,$dbName);
if(mysqli_connect_errno($con)) {
    header('Location: ../DBError.html');
}

if(empty($_REQUEST['screenName']))
    $id = $_SESSION['user'];
else {

    if(stripos($_REQUEST['screenName'],"@")===0) {
        $rs = mysqli_query($con,"select * from user where screen_name='".strtok($_REQUEST['screenName'],"@")."'");
    } else {
        $rs = mysqli_query($con,"select * from user where name='".$_REQUEST['screenName']."'");
    }
    
    if($rs =  mysqli_fetch_array($rs)) {
        $id = $rs['id'];
    } else {
        header('Location: DBError.html');
    }
}

$rs = mysqli_query($con,"select * from user where id='".$id."'");

if($rs =  mysqli_fetch_array($rs)) {
    $user = new User($rs);
} else {
    header('Location: ../DBError.html');
}
mysqli_close($con);

echo json_encode(array("bgColor"=>$user->get_profile_background_color()));
?>
