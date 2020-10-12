<?php namespace Yfktn\EventGubernur\Components;

use Carbon\Carbon;

/**
 * Tampilkan daftar kegiatan pada bulan dan tahun terpilih!
 * @package Yfktn\EventGubernur\Components
 */
class DaftarKegiatanPadaBulan extends DaftarKegiatanPada
{
    protected $theNow;
    
    protected $month;
    
    protected $year;

    public function componentDetails()
    {
        return [
            'name'        => 'Daftar Kegiatan Bulanan',
            'description' => 'Menampilkan daftar kegiatan pada suatu bulan.'
        ];
    }

    public function defineProperties()
    {
        return [
            'month' => [
                'title'       => 'Bulan',
                'description' => 'Tentukan bagian URL untuk menunjukkan bagian bulan',
                'type'        => 'string',
                'default'     => '{{ :month }}',
            ],
            'year' => [
                'title'       => 'Tahun',
                'description' => 'Tentukan bagian URL untuk menunjukkan bagian tahun',
                'type'        => 'string',
                'default'     => '{{ :year }}',
            ],
            'postsPerPage' => [
                'title'             => 'Jumlah Daftar Per Halaman',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Pastikan nilainya benar',
                'default'           => '10',
            ],
            'detailPage' => [
                'title'       => 'Halaman detail',
                'description' => 'Halaman detail saat judul kegiatan di klik',
                'default'     => 'jadwal/detail',
                'type'        => 'string'
            ],
            'tidakAdaJadwal' => [
                'title'       => 'Pesan Tidak Ada Jadwal',
                'description' => 'Pesan bila tidak ada jadwal',
                'default'     => 'Tidak ada jadwal dimasukkan',
                'type'        => 'string'
            ],

        ];
    }

    protected function prepareVars()
    {
        $this->frontEndTimezone = config('yfktn.eventgubernur::defaultFrontEndTZ');
        
        $this->theNow = Carbon::now($this->frontEndTimezone);

        // $this->pageParam = $this->page['pageParam'] = $this->param($this->paramName('currPage'));
        $this->tidakAdaJadwal = $this->page['tidakAdaJadwal'] = $this->property('tidakAdaJadwal');

        $this->month = intval($this->property('month', 0));
        $this->year = intval($this->property('year', 0));

        $lastDay = 30;
        if($this->month == null || $this->month == 0 || $this->year == null || $this->year == 0) {
            $this->month = $this->theNow->month;
            $this->year = $this->theNow->year;
            $lastDay = $this->theNow->daysInMonth;
        } else {
            $this->theNow = Carbon::createFromDate(
                $this->year, $this->month, 1, $this->frontEndTimezone
            );
            $lastDay = $this->theNow->daysInMonth;
        }

        $this->fromDate = "{$this->year}-{$this->month}-1";
        $this->toDate = "{$this->year}-{$this->month}-{$lastDay}";
        if (!$this->toDate || $this->toDate == null) {
            $this->toDate = $this->fromDate;
        }

        $this->page['fromDate'] = $this->fromDate;
        $this->page['toDate'] = $this->toDate;

        // bikin link!
        $this->detailPage = $this->page['linkKeDetail'] = $this->property('detailPage');
        $this->page['theNow'] = Carbon::now($this->frontEndTimezone);
        // dd($this->fromDate, $this->toDate);
    }

}