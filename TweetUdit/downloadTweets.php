<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once ('User.php');
require_once ('Tweet.php');
require_once ('mPDF/mpdf.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: clearsession.php');
}

$user = unserialize($_SESSION['user']);


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
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <h4 class="navbar-text">TweetUdit</h4>
                    <span id="liName" class="navbar-text">'.$user->get_name().'</span>
                    <span id="liScreenName" class="navbar-text">&nbsp;<strong><em>(@'.$user->get_screen_name().')</em></strong></span>
                    <img class="img-polaroid" src="'.$user->get_profile_image_url().'">
                </div>
            </div>
        </div>

        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>

        <div class="container">
            <div class="container-fluid row">
                <div id="wall" class="container-fluid span9">
                    <h4 class="offset3">My Home Timeline</h4>';

foreach ($user->get_tweets_object() as $tweet) {

    $html = $html . '
                    <div class="container-fluid tweet-box item">
                        <div class="container-fluid row tweet-text">
                            <div class="container-fluid span">
                                <img class="img-polaroid" src="'.$tweet->get_user_profile_image().'">
                                <span><em><strong> @'.$tweet->get_user().' : </strong></em>'.$tweet->get_created_at().'</span>
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

$mpdf->Output();

exit;
?>