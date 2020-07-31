<?php
namespace App\Controllers;

use Core\BaseController;
use Core\Session;
use Core\Redirect;



class HomeController extends BaseController
{
    
    public function index(){
        
        $idUser = Session::get('idUser');
        
        if(isset($idUser) && !empty($idUser)){
            $this->view->idUser = $idUser;
            
        }

        $this->setPageTitle('Home');
        return $this->loadView('Home/index', "layout");
    }
}

