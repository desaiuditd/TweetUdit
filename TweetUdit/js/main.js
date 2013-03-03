/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    $.post("services/getName.php",function(data) {
        $("title").append(" | "+data.name);
        $("#liName").html(data.name);
    },"json");

    $.post("services/getScreenName.php",function(data) {
        $("#liScreenName").html("&nbsp;<strong><em>(@"+data.screenName+")</em></strong>");
    },"json");

    $.post("services/getProfileImageURL.php",function(data) {
        $("#profile_pic").append("<img class='img-polaroid' src='"+data.profileImageURL+"'>");
    },"json");

    $.post("services/getBackgroundColor.php",function(data) {
        $("body").css("background-color","#"+data.bgColor);
    },"json");

    $.post("services/getSidebarColor.php",function(data) {
        $("#wall,#followers").css("background-image","-moz-linear-gradient(top, #"+data.sbColor+", #FEFEFE)");
        $("#wall,#followers").css("background-image","-webkit-gradient(linear, 0 0, 0 100%, to(#"+data.sbColor+"), from(#FEFEFE))");
        $("#wall,#followers").css("background-image","-webkit-linear-gradient(top, #"+data.sbColor+", #FEFEFE)");
        $("#wall,#followers").css("background-image","-o-linear-gradient(top, #"+data.sbColor+", #FEFEFE)");
        $("#wall,#followers").css("background-image","linear-gradient(to bottom, #"+data.sbColor+", #FEFEFE)");
        $("#wall,#followers").css("background-repeat","repeat-x");
    },"json");

    $.post("services/getBackgroundImageURL.php",function(data) {
      $("body").css("background-image","url('"+data.bgImageURL+"')");
      $("body").css("background-repeat","no-repeat");
      $("body").css("background-position","0% -15%");
    },"json");

    $.post("services/getTweetsHomeTimeline.php",function(data) {
        var source = $("#tmpltTweets").html();
        var template = Handlebars.compile(source);
        var html = template(data);
        $("#wall h4").after(html);
        $("#divTweets").carousel("cycle");
    },"json");

    $.post("services/getFollowers.php",function(data) {
        var source = $("#tmpltFollowers").html();
        var template = Handlebars.compile(source);
        var html = template(data);
        $("#followers").append(html);
    },"json");
});