<?php namespace YanFriskantoni\EventGubernur\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class JadwalGubernur extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'Entri Event' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('YanFriskantoni.EventGubernur', 'mm-jadwal-gub');
    }
}
