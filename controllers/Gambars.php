<?php namespace YanFriskantoni\EventGubernur\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Gambars extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'Entri Gambar Depan' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('YanFriskantoni.EventGubernur', 'mm-jadwal-gub', 'side-menu-item-gambars');
    }
}
