<?php namespace YanFriskantoni\EventGubernur\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ApplicationException;
use Carbon\Carbon;
use YanFriskantoni\EventGubernur\Models\EGItem as ItemKegiatan;

class CalendarGub extends ComponentBase
{
	public $month;
	public $year;
	public $theData;
		
    public function componentDetails()
    {
        return [
            'name'        => 'Kalender',
            'description' => 'Menampilkan kalender kegiatan Gub.'
        ];
    }

    public function defineProperties()
    {
        return [
            'tmonth' => [
                'title'       => 'Variable Bulan',
                'description' => 'Nama bagian bulan di URL',
                'type'        => 'string',
                'default'     => '{{ :month }}'
            ],
            'tyear' => [
                'title'       => 'Variable Tahun',
                'description' => 'Nama bagian tahun di URL',
                'type'        => 'string',
                'default'     => '{{ :year }}'
            ],
			'linkRange' => [
                'title'       => 'Link Range',
                'description' => 'URL ke halaman daftar berdasarkan range',
                'type'        => 'string',
                'default'     => 'jadwal/range'
            ],
		];
    }
	
	public function onRun() 
	{
		$this->prepareVars();
		$this->loadCalendar();
	}
	
	protected function prepareVars()
	{
		$this->month = (int) $this->param($this->paramName("tmonth"));
		$this->year  = (int) $this->param($this->paramName("tyear"));
		
		$this->month = $this->month == 0 ? date("m") : $this->month;
		$this->year = $this->year == 0 ? date("Y") : $this->year;
		
		$this->page['currMonth'] = $this->month;
		$this->page['currYear'] = $this->year;
		$this->page['linkRange'] = $this->property('linkRange');
	}
	
	protected function setPrevNextURL($currMonth, $currYear) 
	{
		$prev = Carbon::createFromDate($currYear, $currMonth, 1)->subMonth();
		$this->page['prevMonth'] = $prev->month;
		$this->page['prevYear'] = $prev->year;
		$next = $prev->addMonth(2);
		$this->page['nextMonth'] = $next->month;
		$this->page['nextYear'] = $next->year;
	}
	
	protected function setDataKegiatan($theNow) 
	{
		$start = $theNow->year . '-' . $theNow->month . '-1';
		$end = $theNow->year . '-' . $theNow->month . '-' . $theNow->daysInMonth;
		$daftar = ItemKegiatan::whereBetween('tgl_mulai', [ $start, $end ] )
			->selectRaw('DAY(tgl_mulai) as day, count(tgl_mulai) as sum')
			->groupBy('tgl_mulai')
			->get();
		$data = [];
		foreach($daftar as $d) {
			$data[$d->day] = $d->sum;
		}
		$this->page['daftarKegiatan'] = $data;
	}
	
	protected function loadCalendar()
	{
		$theData = [];
		$now = Carbon::createFromDate($this->year, $this->month, 1);
		$this->setPrevNextURL($now->month, $now->year);
		$this->setDataKegiatan($now);
		$daysInMonth = $now->daysInMonth;
		$dayOfFirstMonth = Carbon::createFromDate($now->year, $now->month, 1)->format("N");
		$daysInPrevMonth = $now->subMonth()->daysInMonth;
		$daysInPrevMonth -= ($dayOfFirstMonth-2); // to fill in the blank month
		$num = $weeks = $other = 0;
		do {
			++$weeks;
			for($i=1;$i<8;$i++) {
				if($num <= 0 && $i == $dayOfFirstMonth) {
					$theData[$weeks][$i] = ++$num;
				} else if($num <= 0 && $i < $dayOfFirstMonth) {
					$theData[$weeks][$i] = -1; // $daysInPrevMonth++; // days before current month
				} else {
					if(++$num > $daysInMonth) {
						$theData[$weeks][$i] = -1; // ++$other;
					} else {
						$theData[$weeks][$i] = $num;
					}
				}
			}
		} while($num <= $daysInMonth);
		$this->page['theData'] = $theData;
	}
}