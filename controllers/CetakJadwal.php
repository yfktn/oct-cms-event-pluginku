<?php namespace YanFriskantoni\EventGubernur\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Input;
use Redirect;
use Backend;
use YanFriskantoni\EventGubernur\Models\EGItem as ItemKegiatan;

class CetakJadwal extends Controller
{
    public $implement = [        'Backend\Behaviors\FormController'    ];
    
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'Cetak Event' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('YanFriskantoni.EventGubernur', 'mm-jadwal-gub', 'side-menu-item-cetak');
    }
	
	public function onBeforeCetak() 
	{
		$params = [
			'tgl_dari' => Input::get('EGItem.tgl_dari'),
			'tgl_sd' => Input::get('EGItem.tgl_sd'),
		];
		//return $params;
		$param = Backend::url('yanfriskantoni/eventgubernur/cetakjadwal/cetak');
		$url = "{$param}?tgl_dari=".Input::get('EGItem.tgl_dari').'&'.'tgl_sd='.Input::get('EGItem.tgl_sd');
		//return $param;
		//return $this->controller->pageUrl(Backend::url('yanfriskantoni/eventgubernur/cetakjadwal/cetak'), [
		//	'tgl_dari' => Input::get('EGItem.tgl_dari'),
		//	'tgl_sd' => Input::get('EGItem.tgl_sd')
		//	]);
		return Redirect::to($url);
		//return Redirect::to(Backend::url('yanfriskantoni/eventgubernur/cetakjadwal/cetak'));
	}
	
	public function cetak() 
	{
		$this->layout = 'pencetakan';
		$input = Input::all();
		$this->vars['input'] = $input;
		$this->vars['data'] = $this->getData($input['tgl_dari'], $input['tgl_sd']);
		
		return $this->makePartial('main');
	} 
	
	protected function getData($tgl_dari, $tgl_sd) 
	{
        $daftarKegiatan = new ItemKegiatan;
				
        $daftarKegiatan = $daftarKegiatan
			->where('tgl_mulai', '>=', $tgl_dari)
			->where('tgl_mulai', '<=', $tgl_sd)
			->orderBy('tgl_mulai', 'DESC')->orderBy('jam_mulai', 'ASC');
			
		return $daftarKegiatan->get();
	}
}
