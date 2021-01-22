<?php
/**
 * Custom api untuk fullcalendar support dan sejenisnya
 */

use Yfktn\EventGubernur\Components\FullCalendar;

\Route::group(['prefix'=>'api/yfktn/eventgubernur/fullcalendar/'], function() {

    \Route::any('events', function() {
        // lakukan pengambilan events di sini!
        return response()->json(FullCalendar::onGetEvents());
    });
});