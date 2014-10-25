<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
require_once('TwitterConfig.php');
require_once('DBConfig.php');
require_once('User.php');
require_once('Tweet.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: clearsession.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

$id = $_SESSION['user'];

$con = mysqli_connect($host,$username,$password,$dbName);
if(mysqli_connect_errno($con)) {
    header('Location: DBError.html');
}

$rs = mysqli_query($con,"select * from user where id='".$id."'");

if($rs =  mysqli_fetch_array($rs)) {
    $user = new User($rs);
} else {
    header('Location: DBError.html');
}


/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

if(!empty($_POST['unFavTweet'])) {
    $unFavs = $_POST['unFavTweet'];

    foreach ($unFavs as $tweetID) {
        $connection->post("favorites/destroy",array("id"=>$tweetID,"include_entities"=>false));
    }
}

$data = $connection->get("favorites/list",array("user_id"=>$user->get_id(),"count"=>20,"include_entities"=>false));

$fav = array();

if(count($data)!=0 && is_array($data)) {
    foreach ($data as $tweet) {
        $fav[] = new Tweet($tweet->id_str, $tweet->text, $tweet->user->screen_name, $tweet->user->profile_image_url, $tweet->created_at);
    }
}
?>

<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>TweetUdit | Favorites</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
                background-image: url('<?php echo $user->get_profile_background_image_url();?>');
                background-repeat: no-repeat;
/*                background-position: 0% -15%;*/
                background-color: <?php echo "#".$user->get_profile_background_color();?>;
            }
            #wall {
                background-color: <?php echo "#".$user->get_profile_sidebar_fill_color()?>;
            }
            footer {
                background-color: #fefefe;
                margin-left: 20px;
                margin-right: 20px;
            }
            hr {
                margin-top: 10px;
                margin-bottom: 5px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.css">
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
                        <li id="liName" class="navbar-text"><?php echo $user->get_name();?></li>
                        <li id="liScreenName" class="navbar-text">&nbsp;<strong><em><a href="home.php">(@<?php echo $user->get_screen_name();?>)</a></em></strong></li>
                    </ul>
                    <div class="nav dropdown  pull-right">
                        <a class="navbar-text dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" href="#">
                            <i class="icon-wrench"></i><span>User Actions</span>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a tabindex="-1" href="manageFav.php"><i class="icon-star"></i> Manage Favorites</a></li>
                            <li><a tabindex="-1" href="clearsession.php"><i class="icon-remove-circle"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div id="wall" class="container span9 offset2">
                <div class="container-fluid tweet-box">
                    <form action="manageFav.php" method="post">
                        <button class="btn btn-danger pull-right" type="submit">Unfavorite</button>
                        <table id="tblFavTweets" class="table table-hover table-striped">
                            <caption>Top <?php echo count($fav)?count($fav):"";?> Favorite Tweets</caption>
                            <thead>
                                <tr>
                                    <th class="span1"><input id="checkAll" type="checkbox"></th>
                                    <th>Tweets</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    if(count($fav)!=0) {
        foreach ($fav as $tweet) {
?>
                                <tr>
                                    <td class="span1"><input value="<?php echo $tweet->get_id();?>" type="checkbox" name="unFavTweet[]"></td>
                                    <td>
                                        <div class="container-fluid span">
                                            <img class="img-polaroid" src="<?php echo $tweet->get_creater_profile_image();?>">
                                            <span><em><strong> @<?php echo $tweet->get_created_by();?> : </strong></em><?php echo $tweet->get_created_at();?></span>
                                        </div>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="container-fluid span">
                                            <?php echo $tweet->get_text();?>
                                        </div>
                                    </td>
                                </tr>
<?php
        }
    }
    else {
?>
                                <tr class="error">
                                    <td></td>
                                    <td class=""> Oops ... No Favorite Tweets !!</td>
                                </tr>
<?php
    }
?>
                            </tbody>
                        </table>
                        <button class="btn btn-danger pull-right" type="submit">Unfavorite</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div><div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div><div class="clearfix">&nbsp;</div>

        <div id="footer">
	        <div class="container">
		        <p class="muted credit pull-right">&copy; <a href="http://blog.incognitech.in">Udit Desai</a></p>
	        </div>
        </div>
    </body>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/manageFav.js"></script>

</html>