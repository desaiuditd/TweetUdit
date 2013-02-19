<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once ('../User.php');

$user = unserialize($_SESSION['user']);
echo json_encode(array("screenName"=>$user->get_screen_name()));
?>
