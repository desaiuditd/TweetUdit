<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitteroauth/config.php');
require_once ('User.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: clearsession.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$user = new User($connection->get('account/verify_credentials'));
$_SESSION['user']=serialize($user);

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>rtCamp Test</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">
        

        <script src="js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser.
        Please <a href="http://browsehappy.com/">upgrade your browser</a> or
        <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
 
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <span class="brand">rtCamp Test</span>
                    <ul class="nav">
                        <li class="divider-vertical"></li>
                        <li id="liName" class="navbar-text"></li>
                        <li id="liScreenName" class="navbar-text"></li>
                    </ul>
                    <a href="clearsession.php" class="btn btn-danger pull-right">Logout</a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="container-fluid row">
                <div id="profile_pic" class="container-fluid span"></div>
                <div id="wall" class="container-fluid span9">
                    <h4 class="offset3">My Home Timeline</h4>
                </div>
            </div>
            <div class="container navbar navbar-fixed-bottom">
                <hr>
                <footer>
                    <p class="pull-right">&copy; Udit Desai</p>
                </footer>
            </div>
        </div>
    </body>

    <script id="tmpltTweets" type="text/x-handlebars-template">
        <div id="divTweets" class="carousel slide">
            <div class="carousel-inner">
                {{#each this}}
                    <div class="container-fluid tweet-box item">

                        <div class="container-fluid row tweet-text">
                            <div class="container-fluid span">
                                <img class="img-polaroid" src="{{user_profile_image}}">
                                <span><em><strong> @ {{user}} : </strong></em>{{created_at}}</span>
                            </div>
                            <div class="container-fluid span" style="margin-top: 1%">
                                {{text}}
                            </div>
                        </div>
                    </div>
                {{/each}}
            </div>
            <a class="carousel-control left" href="#divTweets" data-slide="prev">&lsaquo;</a>
            <a class="carousel-control right" href="#divTweets" data-slide="next">&rsaquo;</a>
        </div>
    </script>

    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/handlebars.js"></script>
    <script src="js/main.js"></script>

</html>