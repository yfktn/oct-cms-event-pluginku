<?php namespace Yfktn\EventGubernur\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Cms\Classes\Controller;
use Cms\Classes\Page;
use LogicException;
use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;
use Yfktn\EventGubernur\Models\EGItem as EventsModel;
/**
 * Hi ini catatan untuk component ini.
 * - bahwa query dari fullcalendar untuk mendapatkan events pada rentang waktu yang ada
 *   menggunakan format ISO8601, dengan variable start dan end
 * - bahwa dari request tersebut memiliki format string ISO8601
 * - bahwa untuk melakukan query ke database, maka lakukan terlebih dahulu perubahan
 *   timezone dari TZ User Frontend -> TZ System
 * - setelah didapatkan hasil dari database, lakukan kembali konversi ke TZ user frontend
 *   yaitu TZ System -> TZ User Frontend
 * - untuk mendapatkan hasil yang konsisten maka terkait penggunaan tanggal dan waktu 
 *   pada inputan tanggal dan jam, tidak lagi ignoreTimeZone bernilai true
 * @package Yfktn\EventGubernur\Components
 */
class FullCalendar extends ComponentBase
{

    public function componentDetails() 
    {
        return [
            'name'        => 'FrontEnd fullcalender',
            'description' => 'Menampilkan menggunakan fullcalender.'
        ];
    }

    public function defineProperties()
    {
        return [
            'detailPage' => [
                'title'       => 'Halaman detail',
                'description' => 'Halaman detail saat judul kegiatan di klik',
                'default'     => 'jadwal/detail',
            ],

        ];
    }

    /**
     * Load daftar halaman detailnya!
     * @return mixed 
     */
    public function getDetailPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Ini akan dipanggil dari custom Route! Karena ini dipanggil dari custom route
     * maka kita tidak bisa melakukan render terhadap pageUrl karena controller tidak
     * ada.
     * @return array 
     * @throws LogicException 
     * @throws BindingResolutionException 
     * @throws InvalidArgumentException 
     */
    public function onGetEvents()
    {
        $start = input('start');
        $end = input('end');
        // dd($start, $end);
        // trace_log($start, $end);
        $systemTZ = config("app.timezone");
        // karena di tanggal yang diberikan waktu fullcalendar melakukan permintaan
        // events, pada string yang diberikan sudah ada informasi timezone nya
        // sehingga kita tidak perlu lagi melakukan proses setting manual timezone
        $startTZ = Carbon::parse($start);
        $endTZ = Carbon::parse($end);
        $frontEndTimeZone = $startTZ->timezone;
        // trace_log($startTZ, $endTZ);
        // trace_sql();
        // dapatkan dari db
        $events = EventsModel::whereBetween('tgl_mulai', [
                $startTZ->copy()->timezone($systemTZ), 
                $endTZ->copy()->timezone($systemTZ)])
            ->get();
        // loop untuk melakukan render ke JSON nya
        $data = [];
        $i = 0;
        foreach ($events as $e) {
            $satuHari = false;
            $data[$i]['id'] = $e->id;
            $data[$i]['title'] = $e->judul;
            $data[$i]['slug'] = $e->slug;
            $theStart = Carbon::parse("{$e->tgl_mulai} {$e->jam_mulai}", $systemTZ);
            if ($e->tgl_selesai == null) {
                // satu hari
                if ($e->jam_selesai == null) {
                    // satu hari?
                    $theEnd = $theStart->copy();
                    $satuHari = true;
                } else {
                    $theEnd = $theStart->copy()->setTimeFromTimeString($e->jam_selesai);
                }
            } else {
                if ($e->jam_selesai == null) {
                    $theEnd = Carbon::parse("{$e->tgl_selesai}", $systemTZ);
                } else {
                    $theEnd = Carbon::parse("{$e->tgl_selesai} {$e->jam_selesai}", $systemTZ);
                }
            }
            // kalau di set satu hari, maka set pada waktu time telah dirubah timezone nya!
            if($satuHari) {
                // $theEnd->setTime(
                //     23, 59, 59
                // );
                $data[$i]['start'] = $theStart->timezone($frontEndTimeZone)->format("Y-m-d");
                // untuk satu hari nilai end tidak perlu ditambahkan!
                // $data[$i]['end'] = null;
            } else {
                // dapatkan, convert ke timezone si front end dan set formatnya
                $data[$i]['start'] = $theStart->timezone($frontEndTimeZone)->toIso8601String();
                $theEnd->timezone($frontEndTimeZone);
                if($e->jam_selesai == null) {
                    // set di sini supaya menunjukkan sampai akhir hari itu / full satu hari!
                    $theEnd->endOfDay();
                }
                $data[$i]['end'] = $theEnd->toIso8601String();
            }
            $i = $i + 1;
        }
        return $data;
    }

    public function onRun()
    {
        // lakukan penambahan assets
        $this->addCss('/plugins/yfktn/eventgubernur/assets/fullcalendar/lib/main.min.css');
        $this->addJs('/plugins/yfktn/eventgubernur/assets/fullcalendar/lib/main.min.js');

        $this->page['detailPage'] = $this->property('detailPage', '#');
    }
    
}