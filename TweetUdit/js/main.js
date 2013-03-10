/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var opts = {
  lines: 11, // The number of lines to draw
  length: 19, // The length of each line
  width: 6, // The line thickness
  radius: 10, // The radius of the inner circle
  corners: 1, // Corner roundness (0..1)
  rotate: 0, // The rotation offset
  color: '#FFF', // #rgb or #rrggbb
  speed: 1, // Rounds per second
  trail: 44, // Afterglow percentage
  shadow: true, // Whether to render a shadow
  hwaccel: false, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: '100', // Top position relative to parent in px
  left: 'auto' // Left position relative to parent in px
};
var spinner = new Spinner(opts);
var flag=false;
var interval=0;

function init() {

    try {
        $.post("services/getName.php",function(data) {
            $("title").append(" | "+data.name);
            $("#liName").html(data.name);

            $.post("services/getScreenName.php",function(data) {
                $("#liScreenName").html("&nbsp;<strong><em><a href='home.php'>(@"+data.screenName+")</a></em></strong>");

                $.post("services/getProfileImageURL.php",function(data) {
                    $("#profile_pic").append("<a href='home.php'><img class='img-polaroid' src='"+data.profileImageURL+"'></a>");

                    $.post("services/getBackgroundColor.php",function(data) {
                        $("body").css("background-color","#"+data.bgColor);
                        $("#footer").css("background-color","#"+data.bgColor);

                        $.post("services/getSidebarColor.php",function(data) {
                            $("#wall,#followers").css("background-image","-moz-linear-gradient(top, #"+data.sbColor+", #FEFEFE)");
                            $("#wall,#followers").css("background-image","-webkit-gradient(linear, 0 0, 0 100%, to(#"+data.sbColor+"), from(#FEFEFE))");
                            $("#wall,#followers").css("background-image","-webkit-linear-gradient(top, #"+data.sbColor+", #FEFEFE)");
                            $("#wall,#followers").css("background-image","-o-linear-gradient(top, #"+data.sbColor+", #FEFEFE)");
                            $("#wall,#followers").css("background-image","linear-gradient(to bottom, #"+data.sbColor+", #FEFEFE)");
                            $("#wall,#followers").css("background-repeat","repeat-x");

                            $.post("services/getBackgroundImageURL.php",function(data) {
                                $("body").css("background-image","url('"+data.bgImageURL+"')");
                                $("body").css("background-repeat","no-repeat");
                                $("body").css("background-position","0% -15%");

                                $.post("services/getTweetsHomeTimeline.php",function(data) {
                                    var source = $("#tmpltTweets").html();
                                    var template = Handlebars.compile(source);
                                    var html = template(data);
                                    $("#wall h4").after(html);
                                    $("#divTweets").carousel("cycle");

                                    $("#wall #divTweets").after('<div class="container-fluid pull-right"><a href="downloadTweets.php" class="btn btn-primary">Download Tweets</a></div>');

                                    flag = true;
                                },"json");
                            },"json");
                        },"json");
                    },"json");
                },"json");
            },"json");
        },"json");
    } catch(e) { alert(e.message); }
}

function spinnerStopAPI() {
    spinner.stop();
}
function stopSpinner() {
    if(flag==true) {
        setTimeout(spinnerStopAPI, 3000);
    } else setTimeout(stopSpinner, 3000);
}

$(document).ready(function() {

    spinner.spin(document.body);
    flag = false;
    setTimeout(stopSpinner, 3000);

    init();

    $(document).on("click",".follower",function(e) {

        spinner.spin(document.body);
        flag = false;
        setTimeout(stopSpinner, 3000);

        var screenName = $(this).html();
        $.post("services/getTweetsUserTimeline.php",{screen_name : screenName},function(data) {

            var source = $("#tmpltHeader").html();
            var template = Handlebars.compile(source);
            var html = template({user : screenName});
            $("#wall").html(html);

            source = $("#tmpltTweets").html();
            template = Handlebars.compile(source);
            html = template(data);
            $("#wall").append(html);
            $("#divTweets").carousel("cycle");
            flag = true;
        },"json");
    });

    $("#typeahead").typeahead({
        source : function (query,process) {
                    spinner.spin(document.body);
                    flag = false;
                    setTimeout(stopSpinner, 3000);
                    $.post("services/getFollowers.php",{ query : query },function(data) {
                        process($.parseJSON(data));
                        flag = true;
                    });
                },
        minLength : 4,
        updater : function(item) {
                    spinner.spin(document.body);
                    flag = false;
                    setTimeout(stopSpinner, 3000);

                    var screenName = "@"+item;
                    $.post("services/getTweetsUserTimeline.php",{screen_name : screenName},function(data) {
                        var source = $("#tmpltHeader").html();
                        var template = Handlebars.compile(source);
                        var html = template({user : screenName});
                        $("#wall").html(html);

                        source = $("#tmpltTweets").html();
                        template = Handlebars.compile(source);
                        html = template(data);
                        $("#wall").append(html);
                        $("#divTweets").carousel("cycle");
                        flag = true;
                    },"json");
                    return item;
                }
    });
});