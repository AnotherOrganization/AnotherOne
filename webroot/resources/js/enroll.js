//Globals
var restricted_times = new Array();

$(function()
{
    //Scheduler config
    var controllerURL = $('#info-controller').data('controllerUrl');

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
    $('.scheduler-pref-time').append(
        '<p class="remove-time-block"><i class="glyphicon glyphicon-ban-circle fix-icon"></i> Monday: 9:00am to 10:00am</p>'
    );
    $('.remove-time-block').click(function(){
        $parent = $('.remove-time-block').parent();
        if($parent.length == 0){
            $parent.append('<p class="no-blocks"></p>');
        }
    });
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

    //My schedule
    var MySchedule = new WeeklySchedule(document.getElementById('mySchedule'));

    MySchedule.emptyTimeBlocks();
    MySchedule.setTableProperties({class: 'table table-bordered table-condensed', style: 'color: black'});
    MySchedule.addBlock('SOEN 341', '8:45', '10:00', 3);
    MySchedule.addBlock('SOEN 341', '8:45', '10:00', 5);
    MySchedule.addBlock('ENGR 242', '11:45', '13:00', 2);
    MySchedule.addBlock('ENGR 242', '11:45', '13:00', 4);
    MySchedule.addBlock('SOEN 341', '12:15', '13:05', 5);
    MySchedule.addBlock('ENGR 371', '10:15', '11:05', 5);
    MySchedule.render();


});