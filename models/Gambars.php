<?php namespace Yfktn\EventGubernur\Models;

use Model;

/**
 * Model
 */
class Gambars extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_eventgubernur_gambars';
	
	/**
	 * untuk gambar yang tampil di depan
	 */
	public $attachOne = [
		'gambar' =>  'System\Models\File',
	];
}
