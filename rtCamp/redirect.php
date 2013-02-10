<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitteroauth/config.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

switch ($connection->http_code) {
    case 200:
        /* Build authorize URL and redirect user to Twitter. */
        $url = $connection->getAuthorizeURL($token);
        echo "<b>url : </b>".$url;
        header('Location: ' . $url); 
        break;
    default:
        /* Error */
        header('Location: error.html'); 
}
?>
