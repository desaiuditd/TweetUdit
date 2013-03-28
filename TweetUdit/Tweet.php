<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Tweet {
    private $id;
    private $text;
    private $created_by;
    private $creater_profile_image;
    private $created_at;

    public function __construct($id, $text, $created_by, $creater_profile_image, $created_at) {
        $this->id = $id;
        $this->text = $text;
        $this->created_by = $created_by;
        $this->creater_profile_image = $creater_profile_image;
        $timestamp = new DateTime($created_at);
        $timestamp->setTimezone(new DateTimeZone("Asia/Kolkata"));
        $this->created_at = $timestamp->format("D M d H:i:s Y");
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

    public function get_created_by() {
        return $this->created_by;
    }

    public function set_created_by($created_by) {
        $this->created_by = $created_by;
    }

    public function get_creater_profile_image() {
        return $this->creater_profile_image;
    }

    public function set_creater_profile_image($creater_profile_image) {
        $this->creater_profile_image = $creater_profile_image;
    }

    public function get_created_at() {
        return $this->created_at;
    }

    public function set_created_at($created_at) {
        $this->created_at = $created_at;
    }

    public function jsonSerialize() {
        return array('id'=>  $this->id,
                    'text'=>  $this->text,
                    'created_by'=>  $this->created_by,
                    'creater_profile_image'=>  $this->creater_profile_image,
                    'created_at'=>  $this->created_at);
    }
}
?>