//Globals
var restricted_times = new Array();

$(function()
{
    $('#scheduler-pref-modal').modal({show:true});
    //Load cookies;

    //User Interface
    var $srch_ctnr = $('.scheduler-search');
    var $srch_cover = $('#scheduler-search-cover');
    var $srch_input = $('#scheduler-search-input');

    $srch_ctnr.mouseenter(function(){
        $srch_cover.slideUp(100);
        $srch_input.slideDown(100);
    });
    $srch_ctnr.mouseleave(function(){
        $srch_cover.slideDown(100);
        $srch_input.slideUp(100);
    });

    //Time Preferences and Modal
    $('.time_add').click(function(){
        var is_complete = true;

        if (!is_complete) {
            $('#scheduler-pref-modal').modal({show: false});
            return;
        }
    });

    $('.time_interval').first().keyup(function(){
    });

    $('.time_remove').click(function(){
    });

    $('#time_all_day').change(function()
    {
        $('.time_interval').prop('disabled', !$('.time_interval').prop('disabled'));
    });



    //Scheduler
    var controllerURL = $('#info-controller').data('controllerUrl');

    //Search
    $srch_input.keyup(function()
    {
        $.ajax({
            method: 'POST',
            url: controllerURL + '/ajax_search_course',
            data: {input: $srch_input.val()},
            success: function(output){
                console.log(output);
            }
        });
    });

});

function TimeBlock(weekday, start, end){
    this.weekday = weekday;
    this.start = start;
    this.end = end;
    this.toString = function(){
        return "Weekday: " + this.weekday + " Start: " + this.start + " End: " + this.end;
    }
}