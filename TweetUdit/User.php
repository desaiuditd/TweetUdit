<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('Tweet.php');

class User {
    private $id;
    private $screen_name;
    private $name;
    private $profile_image_url;
    private $profile_background_image_url;
    private $profile_sidebar_fill_color;
    private $profile_background_color;

    private $tweets = array();

    public function __construct($details) {

        if(!is_array($details)) {
            $this->set_id($details->id_str);
            $this->set_screen_name($details->screen_name);
            $this->set_name($details->name);
            $this->set_profile_image_url($details->profile_image_url);
            $this->set_profile_background_image_url($details->profile_background_image_url);
            $this->set_profile_sidebar_fill_color($details->profile_sidebar_fill_color);
            $this->set_profile_background_color($details->profile_background_color);
        } else {
            $this->set_id($details['id']);
            $this->set_screen_name($details['screen_name']);
            $this->set_name($details['name']);
            $this->set_profile_image_url($details['profile_image_url']);
            $this->set_profile_background_image_url($details['profile_background_image_url']);
            $this->set_profile_sidebar_fill_color($details['profile_sidebar_fill_color']);
            $this->set_profile_background_color($details['profile_background_color']);
        }
    }

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_screen_name() {
        return $this->screen_name;
    }

    public function set_screen_name($screen_name) {
        $this->screen_name = $screen_name;
    }

    public function get_name() {
        return $this->name;
    }

    public function set_name($name) {
        $this->name = $name;
    }

    public function get_profile_image_url() {
        return $this->profile_image_url;
    }

    public function set_profile_image_url($profile_image_url) {
        $this->profile_image_url = $profile_image_url;
    }

    public function get_profile_background_image_url() {
        return $this->profile_background_image_url;
    }

    public function set_profile_background_image_url($profile_background_image_url) {
        $this->profile_background_image_url = $profile_background_image_url;
    }

    public function get_profile_sidebar_fill_color() {
        return $this->profile_sidebar_fill_color;
    }

    public function set_profile_sidebar_fill_color($profile_sidebar_fill_color) {
        $this->profile_sidebar_fill_color = $profile_sidebar_fill_color;
    }

    public function get_profile_background_color() {
        return $this->profile_background_color;
    }

    public function set_profile_background_color($profile_background_color) {
        $this->profile_background_color = $profile_background_color;
    }


    public function get_tweets_json() {

        $data = array();
        foreach ($this->tweets as $tweet) {
            $data[] = $tweet->jsonSerialize();
        }
        return json_encode($data);
    }

    public function get_tweets_object() {
        return $this->tweets;
    }

    public function set_tweets($tweet) {
        $this->tweets[] = $tweet;
    }

    public function jsonSerialize() {

        return array('id'=>  $this->id,
                    'screen_name'=>  $this->screen_name,
                    'name'=>  $this->name,
                    'profile_image_url'=>  $this->profile_image_url,
                    'profile_background_image_url'=>  $this->profile_background_image_url,
                    'profile_sidebar_fill_color'=> $this->profile_sidebar_fill_color,
                    'profile_background_color'=> $this->profile_background_color,
                    'tweets'=> $this->tweets);
    }
}
?>
