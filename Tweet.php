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

    private function getDay($i) {
        switch($i) {
            case 0 : return "Sun";
            case 1 : return "Mon";
            case 2 : return "Tue";
            case 3 : return "Wed";
            case 4 : return "Thurs";
            case 5 : return "Fri";
            case 6 : return "Sat";
        }
    }

    private function getMonth($i) {
        switch($i) {
            case 1 : return "Jan";
            case 2 : return "Feb";
            case 3 : return "Mar";
            case 4 : return "Apr";
            case 5 : return "May";
            case 6 : return "Jun";
            case 7 : return "Jul";
            case 8 : return "Aug";
            case 9 : return "Sept";
            case 10 : return "Oct";
            case 11 : return "Nov";
            case 12 : return "Dec";
        }
    }

    public function __construct($id, $text, $created_by, $creater_profile_image, $created_at) {
        $this->id = $id;
        $this->text = $text;
        $this->created_by = $created_by;
        $this->creater_profile_image = $creater_profile_image;
        $this->created_at = "";
        $this->created_at .= $this->getDay(idate("w", strtotime($created_at)))." ";
        $this->created_at .= $this->getMonth(idate("m", strtotime($created_at)))." ";
        $this->created_at .= idate("t", strtotime($created_at))." ";
        $this->created_at .= idate("H", strtotime($created_at)).":";
        $this->created_at .= idate("i", strtotime($created_at)).":";
        $this->created_at .= idate("s", strtotime($created_at))." ";
        $this->created_at .= idate("Y", strtotime($created_at));
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