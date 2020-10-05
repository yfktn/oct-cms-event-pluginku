<?php

namespace Yfktn\EventGubernur\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ApplicationException;
use Carbon\Carbon;
use Yfktn\EventGubernur\Models\EGItem as ItemKegiatan;

class CalendarGub extends ComponentBase {

    public $month;
    public $year;
    public $theData;
    
    protected $frontEndTimezone = 'Asia/Jakarta';

    public function componentDetails() {
        return [
            'name' => 'Kalender',
            'description' => 'Menampilkan kalender kegiatan Gub.'
        ];
    }

    public function defineProperties() {
        return [
            'tmonth' => [
                'title' => 'Variable Bulan',
                'description' => 'Nama bagian bulan di URL',
                'type' => 'string',
                'default' => '{{ :month }}'
            ],
            'tyear' => [
                'title' => 'Variable Tahun',
                'description' => 'Nama bagian tahun di URL',
                'type' => 'string',
                'default' => '{{ :year }}'
            ],
            'miniType' => [
                'title' => 'Mini type?',
                'description' => 'Render Calender mini?',
                'type' => 'checkbox',
                'default' => 'false'
            ],
            'linkRange' => [
                'title' => 'Link Range',
                'description' => 'URL ke halaman daftar berdasarkan range',
                'type' => 'string',
                'default' => 'jadwal/range'
            ],
        ];
    }

    public function onRun() {
        $this->prepareVars();
        $this->loadCalendar();
    }
    
    public function onRender() {
        if((bool)$this->page['miniType']) {
            return $this->renderPartial('@minitype');
        }
    }

    protected function prepareVars() {
        // set front end timezone
        $this->frontEndTimezone = config('yfktn.eventgubernur::defaultFrontEndTZ');
        
        // berikan ke calendar sesuai dengan front end TZ untuk tanggal hari ini
        $this->page['currDate'] = Carbon::now($this->frontEndTimezone);
        
        $this->month = (int) $this->param($this->paramName("tmonth"));
        $this->year = (int) $this->param($this->paramName("tyear"));

        $this->month = $this->month == 0 ? date("m") : $this->month;
        $this->year = $this->year == 0 ? date("Y") : $this->year;

        $this->page['currMonth'] = $this->month;
        $this->page['currYear'] = $this->year;
        $this->page['linkRange'] = $this->property('linkRange');
        $this->page['miniType'] = (bool) $this->property('miniType', false);
    }

    protected function setPrevNextURL($currMonth, $currYear) {
        $prev = Carbon::createFromDate(
                    $currYear, $currMonth, 1, $this->frontEndTimezone
                )->subMonth();
        $this->page['prevMonth'] = $prev->month;
        $this->page['prevYear'] = $prev->year;
        $next = $prev->addMonth(2);
        $this->page['nextMonth'] = $next->month;
        $this->page['nextYear'] = $next->year;
        // generate URL
        $this->page['prevCalURL'] = $this->controller->currentPageUrl([
            $this->paramName('tmonth') => $this->page['prevMonth'],
            $this->paramName('tyear') => $this->page['prevYear']
        ]);
        $this->page['nextCalURL'] = $this->controller->currentPageUrl([
            $this->paramName('tmonth') => $this->page['nextMonth'],
            $this->paramName('tyear') => $this->page['nextYear']
        ]);
    }

    protected function setDataKegiatan($theNow) {
        // waktu query convert ke timezone di system
        $systemTZ = config("app.timezone");
        $theNowSysTZ = $theNow->timezone($systemTZ);
        $start = $theNow->year . '-' . $theNow->month . '-1';
        $end = $theNow->year . '-' . $theNow->month . '-' . $theNow->daysInMonth;
        $daftar = ItemKegiatan::whereBetween('tgl_mulai', [$start, $end])
                ->selectRaw('DAY(tgl_mulai) as day, count(tgl_mulai) as sum')
                ->groupBy('tgl_mulai')
                ->get();
        $data = [];
        foreach ($daftar as $d) {
            $data[$d->day] = $d->sum;
        }
        $this->page['daftarKegiatan'] = $data;
    }

    protected function loadCalendar() {
        $theData = [];
        $now = Carbon::createFromDate($this->year, $this->month, 1, $this->frontEndTimezone);
        $this->setPrevNextURL($now->month, $now->year);
        $this->setDataKegiatan($now);
        $daysInMonth = $now->daysInMonth;
        $dayOfFirstMonth = Carbon::createFromDate($now->year, $now->month, 1, $this->frontEndTimezone)
                ->format("N");
        $daysInPrevMonth = $now->subMonth()->daysInMonth;
        $daysInPrevMonth -= ($dayOfFirstMonth - 2); // to fill in the blank month
        $num = $weeks = $other = 0;
        do {
            ++$weeks;
            for ($i = 1; $i < 8; $i++) {
                if ($num <= 0 && $i == $dayOfFirstMonth) {
                    $theData[$weeks][$i] = ++$num;
                } else if ($num <= 0 && $i < $dayOfFirstMonth) {
                    $theData[$weeks][$i] = -1; // $daysInPrevMonth++; // days before current month
                } else {
                    if (++$num > $daysInMonth) {
                        $theData[$weeks][$i] = -1; // ++$other;
                    } else {
                        $theData[$weeks][$i] = $num;
                    }
                }
            }
        } while ($num <= $daysInMonth);
        $this->page['theData'] = $theData;
    }

}
