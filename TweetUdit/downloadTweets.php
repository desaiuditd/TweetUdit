<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once ('User.php');
require_once ('Tweet.php');
require_once ('mPDF/mpdf.php');
require_once ('DBConfig.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: clearsession.php');
}

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

$rs = mysqli_query($con,"select * from tweet where user_id='".$id."'");

while($tweet = mysqli_fetch_array($rs)) {
    $user->set_tweets(new Tweet($tweet['id'], $tweet['text'], $tweet['created_by'], $tweet['creater_profile_image'], $tweet['created_at']));
}
mysqli_close($con);


$html = '

    <style>
        #wall {
            background-image: -moz-linear-gradient(top, #'.$user->get_profile_sidebar_fill_color().', #FEFEFE);
            background-image: -webkit-gradient(linear, 0 0, 0 100%, to(#'.$user->get_profile_sidebar_fill_color().'), from(#FEFEFE));
            background-image: -webkit-linear-gradient(top, #'.$user->get_profile_sidebar_fill_color().', #FEFEFE);
            background-image: -o-linear-gradient(top, #'.$user->get_profile_sidebar_fill_color().', #FEFEFE);
            background-image: linear-gradient(to bottom, #'.$user->get_profile_sidebar_fill_color().', #FEFEFE);
            background-repeat: repeat-x;
        }
        body {
            background-image: url('.$user->get_profile_background_image_url().');
            background-repeat: no-repeat;
            background-position: 0% -15%;
            background-color: #'.$user->get_profile_background_color().';
        }

    </style>
    <body>
        <div id="wall" class="container">
            <h4 class="navbar-text">TweetUdit</h4>
            <span id="liName" class="navbar-text">'.$user->get_name().'</span>
            <span id="liScreenName" class="navbar-text">&nbsp;<strong><em>(@'.$user->get_screen_name().')</em></strong></span>
            <img class="img-polaroid" src="'.$user->get_profile_image_url().'">
        </div>

        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>

        <div class="container">
            <div class="container-fluid row">
                <div id="wall" class="container-fluid">
                    <h4>My Home Timeline</h4>';

foreach ($user->get_tweets_object() as $tweet) {

    $html = $html . '
                    <div class="container-fluid tweet-box item">
                        <div class="container-fluid row tweet-text">
                            <div class="container-fluid span">
                                <img class="img-polaroid" src="'.$tweet->get_creater_profile_image().'">
                                <span><em><strong> @'.$tweet->get_created_by().' : </strong></em>'.$tweet->get_created_at().'</span>
                            </div>
                            <div class="container-fluid span" style="margin-top: 1%">
                                '.$tweet->get_text().'
                            </div>
                        </div>
                    </div>';
}

$html = $html.'
                </div>
            </div>
            <div class="container navbar navbar-fixed-bottom">
                <hr>
                <footer>
                    <p class="pull-right">&copy; Udit Desai</p>
                </footer>
            </div>
        </div>
    </body>';

//echo $html;

$mpdf = new mPDF('c');
$mpdf->SetDisplayMode('fullpage');

$css = file_get_contents('css/bootstrap.min.css');
$mpdf->WriteHTML($css,1);
$css = file_get_contents('css/bootstrap-responsive.min.css');
$mpdf->WriteHTML($css,1);
$css = file_get_contents('css/main.css');
$mpdf->WriteHTML($css,1);

$mpdf->WriteHTML($html);

$mpdf->Output("TweetUdit.pdf","D");

exit;
?>