<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Tweet implements JsonSerializable {
    private $id;
    private $text;
    private $user;
    private $user_profile_image;
    private $created_at;

    function __construct($id, $text, $user, $user_profile_image, $created_at) {
        $this->id = $id;
        $this->text = $text;
        $this->user = $user;
        $this->user_profile_image = $user_profile_image;
        $this->created_at = $created_at;
    }

        public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_text() {
        return $this->text;
    }

    public function set_text($text) {
        $this->text = $text;
    }

    public function get_user() {
        return $this->user;
    }

    public function set_user($user) {
        $this->user = $user;
    }

    public function get_user_profile_image() {
        return $this->user_profile_image;
    }

    public function set_user_profile_image($user_profile_image) {
        $this->user_profile_image = $user_profile_image;
    }

    public function get_created_at() {
        return $this->created_at;
    }

    public function set_created_at($created_at) {
        $this->created_at = $created_at;
    }

    public function jsonSerialize() {
        return ['id'=>  $this->id,'text'=>  $this->text,'user'=>  $this->user,'user_profile_image'=>  $this->user_profile_image,'created_at'=>  $this->created_at];
    }
}
?>