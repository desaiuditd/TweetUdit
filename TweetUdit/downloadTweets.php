<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once ('User.php');
require_once ('Tweet.php');
require_once ('mPDF/mpdf.php');

$user = unserialize($_SESSION['user']);


$html = '
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <span class="brand">TweetUdit</span>
                    <ul class="nav">
                        <li class="divider-vertical"></li>
                        <li id="liName" class="navbar-text">'.$user->get_name().'</li>
                        <li id="liScreenName" class="navbar-text">&nbsp;<strong><em>(@'.$user->get_screen_name().')</em></strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="container-fluid row">
                <div id="profile_pic" class="container-fluid span"></div>
                <div id="wall" class="container-fluid span9">
                    <h4 class="offset3">My Home Timeline</h4>';

foreach (json_decode($user->get_tweets()) as $tweet) {
    $html = $html . '
                    <div class="container-fluid tweet-box item">
                        <div class="container-fluid row tweet-text">
                            <div class="container-fluid span">
                                <img class="img-polaroid" src="'.$tweet->get_user_profile_image().'">
                                <span><em><strong> @ '.$tweet->get_user().' : </strong></em>'.$tweet->gget_created_at().'</span>
                            </div>
                            <div class="container-fluid span" style="margin-top: 1%">
                                '.$tweet->get_text().'
                            </div>
                        </div>
                    </div>';
}

$html = $html.'</div>
            </div>
            <div class="container navbar navbar-fixed-bottom">
                <hr>
                <footer>
                    <p class="pull-right">&copy; Udit Desai</p>
                </footer>
            </div>
        </div>';

//echo $html;

$mpdf = new mPDF('c');
$mpdf->SetDisplayMode('fullpage');

$css = file_get_contents('css/bootstrap-responsive.min.css');
$mpdf->WriteHTML($css,1);
$css = file_get_contents('css/main.css');
$mpdf->WriteHTML($css,1);

$mpdf->WriteHTML($html);

$mpdf->Output();

exit;
?>