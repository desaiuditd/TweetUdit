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

echo json_encode(array("sbColor"=>$user->get_profile_sidebar_fill_color()));
?>
