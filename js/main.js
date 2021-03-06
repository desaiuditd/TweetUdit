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
var flag = false;
var interval = 0;

function updatePage(name) {

    if(name===null) {

        $.post("services/getProfileImageURL.php",function(data) {
            $("#profile_pic a img").attr("src",data.profileImageURL);

            $.post("services/getLinkColor.php",function(data) {
                $(".follower").css("color", "#"+data.linkColor);

                $.post("services/getBackgroundColor.php",function(data) {
                    $("body").css("background-color","#"+data.bgColor);

                    $.post("services/getSidebarColor.php",function(data) {
                        $("#wall,#followers").css("background-color","#"+data.sbColor);

                        $.post("services/getBackgroundImageURL.php",function(data) {
                            $("body").css("background-image","url('"+data.bgImageURL+"')");
                            $("body").css("background-repeat","no-repeat");

                            flag = true;
                        },"json");
                    },"json");
                },"json");
            },"json");
        },"json");
    } else {

        $.post("services/getProfileImageURL.php",{screenName:name},function(data) {
            $("#profile_pic a img").attr("src",data.profileImageURL);
            $("#profile_pic a").attr("href","#");

            $.post("services/getLinkColor.php",{screenName:name},function(data) {
                $(".follower").css("color", "#"+data.linkColor);

                $.post("services/getBackgroundColor.php",{screenName:name},function(data) {
                    $("body").css("background-color","#"+data.bgColor);
                    $("#footer").css("background-color","#"+data.bgColor);

                    $.post("services/getSidebarColor.php",{screenName:name},function(data) {
                        $("#wall,#followers").css("background-color","#"+data.sbColor);

                        $.post("services/getBackgroundImageURL.php",{screenName:name},function(data) {
                            $("body").css("background-image","url('"+data.bgImageURL+"')");
                            $("body").css("background-repeat","no-repeat");
                            $("body").css("background-position","0% -15%");

                            flag = true;
                        },"json");
                    },"json");
                },"json");
            },"json");
        },"json");
    }
}

function init() {

    try {
        $.post("services/getName.php",function(data) {
            $("title").append(" | "+data.name);
            $("#liName").html(data.name);

            $.post("services/getScreenName.php",function(data) {
                $("#liScreenName").html("&nbsp;<strong><em><a href='home.php'>(@"+data.screenName+")</a></em></strong>");

                $.post("services/getTweetsHomeTimeline.php",function(data) {
                    var source = $("#tmpltTweets").html();
                    var template = Handlebars.compile(source);
                    var html = template(data);
                    $("#wall h4").after(html);
                    $("#divTweets").carousel("cycle");

                    updatePage(null);
                },"json");
            },"json");
        },"json");
    } catch(e) {alert(e.message);}
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
            $("#wall h4").remove();
            $("#wall").prepend(html);

            source = $("#tmpltTweets").html();
            template = Handlebars.compile(source);
            html = template(data);
            $("#divTweets").remove();
            $("#wall h4").after(html);
            $("#divTweets").carousel("cycle");

            $("#aDownloadTweets").attr("href","downloadTweets.php?follower="+screenName);

            updatePage(screenName);
        },"json");
    });

    $("#typeahead").typeahead({
        source : function (query,process) {
                    spinner.spin(document.body);
                    flag = false;
                    setTimeout(stopSpinner, 3000);
                    $.post("services/getFollowers.php",{query : query},function(data) {
                        process($.parseJSON(data));
                        flag = true;
                    });
                },
        minLength : 4,
        updater : function(item) {
                    spinner.spin(document.body);
                    flag = false;
                    setTimeout(stopSpinner, 3000);

                    var screenName = item;
                    $.post("services/getTweetsUserTimeline.php",{screen_name : screenName},function(data) {
                        var source = $("#tmpltHeader").html();
                        var template = Handlebars.compile(source);
                        var html = template({user : screenName});
                        $("#wall h4").remove();
                        $("#wall").prepend(html);

                        source = $("#tmpltTweets").html();
                        template = Handlebars.compile(source);
                        html = template(data);
                        $("#divTweets").remove();
                        $("#wall h4").after(html);
                        $("#divTweets").carousel("cycle");

                        $("#aDownloadTweets").attr("href","downloadTweets.php?follower="+screenName);

                        updatePage(screenName);
                        $("#typeahead").val("");
                    },"json");
                    return item;
                }
    });
});