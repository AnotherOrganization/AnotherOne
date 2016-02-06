/**************************************************************************
  ____       _              _       _
 / ___|  ___| |__   ___  __| |_   _| | ___ _ __
 \___ \ / __| '_ \ / _ \/ _` | | | | |/ _ \ '__|
 ___) | (__| | | |  __/ (_| | |_| | |  __/ |
 |____/ \___|_| |_|\___|\__,_|\__,_|_|\___|_|

 */
function WeeklySchedule(elm){

    this.elm = elm;

    this.tableAttr = [];

    this.timeBlocks = [];

    this.increment = 15; //15 minutes

    this.startWeekday = 2; //Monday
    this.endWeekday = 5; //Friday

    this.startTime = '8:15'; //startime
    this.endTime = '13:15'; //endtime

    this.setTableProperties = function(property)
    {
        this.tableAttr = property;
    }

    this.addBlock = function(title, start, end, weekday)
    {
        this.timeBlocks[weekday + ' ' + start] = {
            title: title,
            start: moment(start, 'HH:mm'),
            end: moment(end, 'HH:mm'),
            weekday: weekday
        }
    }

    this.emptyTimeBlocks = function ()
    {
        this.timeBlocks = null;
        this.timeBlocks = [];
    }

    this.render = function()
    {
        this.elm.innerHTML = '';

        /***************************************************************************
                        TABLE ATTRIBUTES
         ***************************************************************************/
        var tbl = '<table';

        for (var attr in this.tableAttr){
            tbl += ' ' + attr + '="' + this.tableAttr[attr] + '"';
        }

        tbl += '>';

        /***************************************************************************
                        WEEKDAYS
        ***************************************************************************/
        tbl += '<tr><td>Time</td>';
        for(var i = this.startWeekday; i < this.endWeekday + 1; i++){ //
            tbl += '<td>' + moment(i, 'd').format('dddd') + '</td>'
        }
        tbl += '</tr>';

        /***************************************************************************
                        THE SCHEDULE
         ***************************************************************************/

        var start = moment(this.startTime, 'HH:mm');
        var end = moment(this.endTime, 'HH:mm');

        var fill = []; //fill is the number of filling <td></tb> blocks on ever row
        for(var row = start.clone(); row.diff(end) != 0; row.add(this.increment, 'm'))
        {
            fill[row.format('H:mm')] = [];
            for (var col = this.startWeekday; col <= this.endWeekday; col++)
            {
                fill[row.format('H:mm')][col.toString()] = 0
            }
        }

        // starts at 8:45 to 14:00 increments by 15 mins
        for(var row = start.clone(); row.diff(end) != 0; row.add(this.increment, 'm'))
        {
            tbl += '<tr>';

            var time = row.format('H:mm');

            tbl += '<td>'+time+'</td>';

            for (var col = this.startWeekday; col <= this.endWeekday; col++)
            { //starts on sunday to saturday
                if(this.timeBlocks[col + ' ' + time] != null){
                    var block = this.timeBlocks[col + ' ' + time];
                    var diff = block['end'].diff(block['start'], 'minute');

                    var rowspan = Math.floor(diff/this.increment);

                    tbl += '<td style="background-color:lightseagreen" rowspan="'+rowspan+'">'+block['title']+'</td>';

                    var re_start = row.clone().add(this.increment, 'm');
                    var re_stop = row.clone().add((rowspan-1) * this.increment, 'm');

                    for(re_start; re_start.diff(re_stop) <= 0; re_start.add(this.increment, 'm')){
                        fill[re_start.format('H:mm')][col.toString()] = 1;
                    }
                    continue;
                }
                if(fill[time][col.toString()] == 0) {
                    tbl += '<td></td>';
                }
            }
            tbl += '</tr>';
        }

        tbl += '</table>';

        elm.insertAdjacentHTML('beforeend', tbl);
    }
}