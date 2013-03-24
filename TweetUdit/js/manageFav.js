/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(e) {
    $("#checkAll").on("change",function(e) {

        if($(this).prop("checked")) {
            $("#tblFavTweets tbody input[type='checkbox']").prop("checked",true);
        } else {
            $("#tblFavTweets tbody input[type='checkbox']").prop("checked",false);
        }
    });
});