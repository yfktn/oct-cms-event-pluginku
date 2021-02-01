<?php namespace Yfktn\EventGubernur\Components;

use Cms\Classes\ComponentBase;
use ApplicationException;
use Yfktn\EventGubernur\Models\EGItem as ItemKegiatan;

class Detail extends ComponentBase
{
    /**
     * untuk referensi kegiatan
     */
    public $kegiatan;

    /**
     * Untuk slug
     */
    public $currentSlug;

    public $frontEndTimezone = "UTC";

    public function componentDetails()
    {
        return [
            'name'        => 'Kegiatan Detail',
            'description' => 'Menampilkan kegiatan detail Pak Gub.'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Setting Slug',
                'description' => 'Nama bagian slug di URL',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
            'listPage' => [
                'title'       => 'Halaman Daftar Kegiatan',
                'description' => 'Link menuju halaman daftar kegiatan',
                'default'     => 'jadwal',
                'type'        => 'string'
            ],
        ];
    }

    public function onRun()
    {
		$this->prepareVars();
        $this->kegiatan = $this->loadKegiatan();
    }
	
	protected function prepareVars() 
	{
        $this->currentSlug = $this->property('slug');
        $this->page['frontEndTimezone'] = config('yfktn.eventgubernur::defaultFrontEndTZ');
	}

    protected function loadKegiatan()
    {
        $slug = $this->property('slug');
        $kegiatan = new ItemKegiatan;
        $kegiatan = $kegiatan->where('slug', $slug)->first();
        return $kegiatan;
    }
}