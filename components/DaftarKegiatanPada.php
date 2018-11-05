<?php namespace YanFriskantoni\EventGubernur\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ApplicationException;
use Carbon\Carbon;
use YanFriskantoni\EventGubernur\Models\EGItem as ItemKegiatan;
/**
 * untuk menampilkan daftar kegiatan pada suatu range
 */
class DaftarKegiatanPada extends ComponentBase
{
    /**
     * untuk referensi kegiatan pada range
     */
    protected $daftarKegiatan;

    protected $pageParam;
	
	protected $tidakAdaJadwal;
	
	protected $detailPage;
	
	protected $fromDate;
	
	protected $toDate;
	
    public function componentDetails()
    {
        return [
            'name'        => 'Daftar Kegiatan Range',
            'description' => 'Menampilkan daftar kegiatan Gubernur pada suatu range.'
        ];
    }

    public function defineProperties()
    {
        return [
            'fromDate' => [
                'title'       => 'Dari Tanggal',
                'description' => 'Tentukan bagian URL untuk menunjukkan bagian range permulaan',
                'type'        => 'string',
                'default'     => '{{ :fromDate }}',
            ],
            'toDate' => [
                'title'       => 'Sampai Tanggal',
                'description' => 'Tentukan bagian URL untuk menunjukkan bagian range akhir',
                'type'        => 'string',
                'default'     => '{{ :toDate }}',
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

    public function onRun()
    {
		$this->prepareVars();
		
        $this->daftarKegiatan = $this->page['daftarKegiatan'] = $this->loadDaftarKegiatan();
    }

    protected function prepareVars() 
    {
        $this->pageParam = $this->page['pageParam'] = $this->param($this->paramName('currPage'));
		$this->tidakAdaJadwal = $this->page['tidakAdaJadwal'] = $this->property('tidakAdaJadwal');
				
		$this->fromDate = $this->param($this->paramName('fromDate'));
		$this->toDate = $this->param($this->paramName('toDate'));
		if($this->toDate === null) {
			$this->toDate = $this->fromDate;
		}
		
		$this->page['fromDate'] = $this->fromDate;
		$this->page['toDate'] = $this->toDate;
		
		// bikin link!
		$this->detailPage = $this->page['linkKeDetail'] = $this->property('detailPage');
		$this->page['theNow'] = date("Y-m-d");
    }

	/**
	 * tampilkan daftar kegiatan yang aktif pada minggu saat ini secara default!
	 */
    protected function loadDaftarKegiatan()
    {
        $daftarKegiatan = new ItemKegiatan;
				
        $daftarKegiatan = $daftarKegiatan
			->where('tgl_mulai', '>=', $this->fromDate)
			->where('tgl_mulai', '<=', $this->toDate)
			->orderBy('tgl_mulai', 'DESC')->orderBy('jam_mulai', 'ASC');
			
		//var_dump($mulaiRenderDari->toDateTimeString(), $sampaiDengan->toDateTimeString());
        return $daftarKegiatan->get();
    }
}