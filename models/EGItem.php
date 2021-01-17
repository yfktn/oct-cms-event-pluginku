<?php namespace Yfktn\EventGubernur\Models;

use Model;
use Carbon\Carbon;
/**
 * Model
 */
class EGItem extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'judul' => ['required'],
        'tgl_mulai' => ['required'],
        'jam_mulai' => ['required'],
        'lokasi' => ['required']
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_eventgubernur_item';

    public $attachMany = [
        'lampiran' => 'System\Models\File',
        'gambar'   => 'System\Models\File'
    ];
	
	/**
	 * untuk cover kegiatan
	 */
	public $attachOne = [
		'cover' =>  'System\Models\File',
	];
	
	/**
	 * ringkasan
	 */
	public function getRingkasanAttribute() {
		return "Peserta dari {$this->peserta} bertempat di {$this->lokasi} dengan pakaian {$this->pakaian}";
	}
}
