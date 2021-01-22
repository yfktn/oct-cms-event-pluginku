<?php namespace Yfktn\EventGubernur\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use LogicException;
use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;
use Yfktn\EventGubernur\Models\EGItem as EventsModel;

class FullCalendar extends ComponentBase
{

    public function componentDetails() 
    {
        return [
            'name'        => 'FrontEnd fullcalender',
            'description' => 'Menampilkan menggunakan fullcalender.'
        ];
    }

    /**
     * Ini akan dipanggil dari custom Route!
     * @return array 
     * @throws LogicException 
     * @throws BindingResolutionException 
     * @throws InvalidArgumentException 
     */
    public static function onGetEvents()
    {
        $start = input('start');
        $end = input('end');
        $frontEndTimeZone = config('yfktn.eventgubernur::defaultFrontEndTZ');
        $systemTZ = config("app.timezone");
        $startTZ = Carbon::parse($start, $frontEndTimeZone)->copy()->timezone($systemTZ);
        $endTZ = Carbon::parse($end, $frontEndTimeZone)->copy()->timezone($systemTZ);

        // dapatkan dari db
        $events = EventsModel::whereBetween('tgl_mulai', [$startTZ, $endTZ])
            ->get();
        // loop untuk melakukan render ke JSON nya
        $data = [];
        foreach ($events as $e) {
            $data['id'] = $e->id;
            $data['title'] = $e->judul;
            $theStart = Carbon::parse("{$e->tgl_mulai} {$e->jam_mulai}", $systemTZ);
            if ($e->tgl_selesai == null) {
                // satu hari
                if ($e->jam_selesai == null) {
                    // satu hari?
                    $theEnd = $theStart->copy()->setTime(23, 59);
                } else {
                    $theEnd = $theStart->copy()->setTimeFromTimeString($e->jam_selesai);
                }
            } else {
                if ($e->jam_selesai == null) {
                    $theEnd = Carbon::parse("{$e->tgl_mulai}", $systemTZ)->setTime(23, 59);
                } else {
                    $theEnd = Carbon::parse("{$e->tgl_mulai} {$e->jam_mulai}", $systemTZ);
                }
            }
            $data['start'] = $theStart->timezone($frontEndTimeZone);
            $data['end'] = $theEnd->timezone($frontEndTimeZone);
        }
        return $data;
    }

    public function onRun()
    {
        // lakukan penambahan assets
        $this->addCss('/plugins/yfktn/eventgubernur/assets/fullcalendar/lib/main.min.css');
        $this->addJs('/plugins/yfktn/eventgubernur/assets/fullcalendar/lib/main.min.js');
    }
    
}