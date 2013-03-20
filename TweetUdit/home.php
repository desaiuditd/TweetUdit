<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitteroauth/config.php');
require_once('DBConfig.php');
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

$con = mysqli_connect($host,$username,$password,$dbName);
if(mysqli_connect_errno($con)) {
    header('Location: DBError.html');
}

$rs = mysqli_query($con,"select id from user where id='".$user->get_id()."'");

if($rs =  mysqli_fetch_array($rs)) {

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

mysqli_close($con);
$_SESSION['user']=$user->get_id();

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
        <title>TweetUdit</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">
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
                    <a href="home.php" class="brand">TweetUdit</a>
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
                <div class="container-fluid span">
                    <div id="profile_pic" class="container-fluid span">
                        <a href='home.php'><img class='img-polaroid' src=""></a>
                    </div>
                    <div id="wall" class="container-fluid span6">
                        <h4 class="offset tweet-box">My Home Timeline</h4>
                        <div class="container-fluid pull-right"><a id="aDownloadTweets" href="downloadTweets.php" class="btn btn-primary">Download Tweets</a></div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="clearfix">&nbsp;</div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="clearfix">&nbsp;</div>
                    <div id="search">
                        <input id="typeahead" class="span6 pull-right" type="text" placeholder="Search Followers ... (Minimum 4 Characters)" data-provide="typeahead" autocomplete="off">
                    </div>
                </div>
                <div id="followers" class="container-fluid span">
                    <h5 class="offset tweet-box">Followers</h5>
                    <div class="container-fluid tweet-box" style="margin-left: 2%; margin-right: 2%">
<?php
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
$followers = $connection->get("followers/list",array("user_id"=>$user->get_id(),"skip_status "=>true,"include_user_entities ",false));

//echo"<pre>";print_r($followers);echo"</pre>";
$i=0;
foreach ($followers->users as $follower) {
    if($i>=10) { break; }
?>
                        <div class="container-fluid row" style="margin-left: 0%; margin-right: 0%;">
                            <img class="img-polaroid" src="<?echo $follower->profile_image_url;?>">
                            <span><a class="follower" href="#">@<?echo $follower->screen_name;?></a></span>
                        </div>
<?php
    $i++;
}
?>
                    </div>
                </div>
            </div>
            <div class="clearfix">&nbsp;</div><div class="clearfix">&nbsp;</div>
            <div id="footer" class="container navbar navbar-fixed-bottom">
                <hr>
                <footer>
                    <p class="pull-right">&copy; Udit Desai</p>
                </footer>
            </div>
        </div>
    </body>

    <script id="tmpltHeader" type="text/x-handlebars-template">
        <h4 class="offset tweet-box">{{user}}'s Timeline</h4>
    </script>

    <script id="tmpltTweets" type="text/x-handlebars-template">
        <div id="divTweets" class="carousel slide">
            <div class="carousel-inner">
                {{#if this}}
                    {{#each this}}
                        <div class="container-fluid tweet-box item">

                            <div class="container-fluid row">
                                <div class="container-fluid span">
                                    {{#if creater_profile_image}}
                                        <img class="img-polaroid" src="{{creater_profile_image}}">
                                        <span><em><strong> @{{created_by}} : </strong></em>{{created_at}}</span>
                                    {{/if}}
                                    {{#if user.profile_image_url}}
                                        <img class="img-polaroid" src="{{user.profile_image_url}}">
                                        <span><em><strong> @{{user.screen_name}} : </strong></em>{{created_at}}</span>
                                    {{/if}}
                                </div>
                                <div class="container-fluid span" style="margin-top: 1%">
                                    {{{text}}}
                                </div>
                            </div>
                        </div>
                    {{/each}}
                {{else}}
                        <div class="container-fluid tweet-box item">
                            Oops ... No Tweets !!!
                        </div>
                {{/if}}
            </div>
            <a class="carousel-control left" href="#divTweets" data-slide="prev">&lsaquo;</a>
            <a class="carousel-control right" href="#divTweets" data-slide="next">&rsaquo;</a>
        </div>
    </script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.0.0-rc.3/handlebars.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/spin.js/1.2.7/spin.min.js"></script>
    <script src="js/main.js"></script>

</html>