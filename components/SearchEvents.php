<?php namespace YanFriskantoni\EventGubernur\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ApplicationException;
use Carbon\Carbon;
use YanFriskantoni\EventGubernur\Models\EGItem as ItemKegiatan;

class SearchEvents extends ComponentBase
{
	
    public function componentDetails()
    {
        return [
            'name'        => 'Pencarian Kegiatan',
            'description' => 'Melakukan pencarian kegiatan.'
        ];
    }
	
	public function defineProperties()
    {
        return [
            'detailPage' => [
                'title'       => 'Halaman detail',
                'description' => 'Halaman detail saat judul kegiatan di klik',
                'default'     => 'kegiatan/detail',
                'type'        => 'string'
            ], /*
            'max' => [
                'description'       => 'The most amount of todo items allowed',
                'title'             => 'Max items',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Max Items value is required and should be integer.'
            ] */
        ];
    }
	
	/**
	 * Lakukan proses pencarian
	 */
	public function onSearch()
	{
		$this->page['theNow'] = date("Y-m-d");
		$this->page['linkKeDetail'] = $this->property("detailPage");
		
		$keywords = $this->page['keywords'] = post('keywords');
		
		if(strlen(trim($keywords)) > 0) {
			$keywords = "%{$keywords}%";			
			// cari 
			$daftarKegiatan = new ItemKegiatan;
					
			$daftarKegiatan = $daftarKegiatan
				->where('judul', 'LIKE', $keywords)
				->orWhere('lokasi', 'LIKE', $keywords)
				->orWhere('peserta', 'LIKE', $keywords)
				->orWhere('pakaian', 'LIKE', $keywords)
				->orWhere('penjelasan', 'LIKE', $keywords)
				->orderBy('tgl_mulai', 'DESC')->orderBy('jam_mulai', 'ASC');
			$this->page['sqlnya'] = $daftarKegiatan->toSql();
			$this->page['daftarKegiatan'] = $daftarKegiatan->get();
		} else {
			$this->page['daftarKegiatan'] = [];
		}
	}
	
}