<?php namespace YanFriskantoni\EventGubernur\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ApplicationException;
use Carbon\Carbon;
use YanFriskantoni\EventGubernur\Models\EGItem as ItemKegiatan;
/**
 * untuk menampilkan daftar kegiatan terbaru
 */
class DaftarKegiatan extends ComponentBase
{
    /**
     * untuk referensi kegiatan
     */
    public $daftarKegiatan;

    public $pageParam;
	
	public $tidakAdaJadwal;
	
	public $detailPage;
	
    public function componentDetails()
    {
        return [
            'name'        => 'Daftar Kegiatan Terbaru',
            'description' => 'Menampilkan daftar kegiatan terbaru Pak Gub.'
        ];
    }

    public function defineProperties()
    {
        return [
            'currPage' => [
                'title'       => 'Bagian URL Untuk Halaman',
                'description' => 'Tentukan bagian URL untuk menunjukkan halaman dari daftar yang aktif',
                'type'        => 'string',
                'default'     => '{{ :page }}',
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
			'tampilMingguKebelakang' => [
                'title'             => 'Minggu Ke Belakang',
				'description'		=> 'Apakah tampil pula daftar sekian minggu ke belakang',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Pastikan nilainya benar',
                'default'           => '0',
            ],
			'tampilBulanKeDepan' => [
                'title'             => 'Bulan Ke Depan',
				'description'		=> 'Berapa bulan ke depan daftar ditampilkan?',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Pastikan nilainya benar',
                'default'           => '12',
            ],
			
        ];
    }

    public function onRun()
    {
		$this->prepareVars();
		
        $this->daftarKegiatan = $this->loadDaftarKegiatan();
    }

    protected function prepareVars() 
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('currPage');
		$this->tidakAdaJadwal = $this->page['tidakAdaJadwal'] = $this->property('tidakAdaJadwal');
		
		// bikin link!
		$this->detailPage = $this->page['detailPage'] = $this->property('detailPage');
		$this->page['theNow'] = date("Y-m-d");
		
    }

	/**
	 * tampilkan daftar kegiatan yang aktif pada minggu saat ini secara default!
	 */
    protected function loadDaftarKegiatan()
    {
        $daftarKegiatan = new ItemKegiatan;
		// minggu ke belakang ...
		$mingguKeBelakang = $this->property('tampilMingguKebelakang');
		$bulanKeDepan = $this->property('tampilBulanKeDepan');
		$mulaiRenderDari = new Carbon(); // default is now!
		$sampaiDengan = new Carbon();
		if($mingguKeBelakang > 0) {
			$mulaiRenderDari = $mulaiRenderDari->subWeeks($mingguKeBelakang); // kurangi ke beberapa minggu ke belakang
		}
		
		if($bulanKeDepan > 0) {
			$sampaiDengan = $sampaiDengan->addMonths($bulanKeDepan); // tampil sampai berapa bulan ke depan?
		} else {
			$sampaiDengan = $sampaiDengan->addMonths(12); // tampil selama 1 tahun
		}
		
        $daftarKegiatan = $daftarKegiatan
			->where('tgl_mulai', '>=', $mulaiRenderDari->toDateTimeString())
			->where('tgl_mulai', '<=', $sampaiDengan->toDateTimeString())
			->orderBy('tgl_mulai', 'DESC')->orderBy('jam_mulai', 'ASC');
			
		//var_dump($mulaiRenderDari->toDateTimeString(), $sampaiDengan->toDateTimeString());
        return $daftarKegiatan->get();
    }
}