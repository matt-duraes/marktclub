<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;
use \src\models\User;


class SearchController extends Controller {
    private $loggedUser;
     //conferir se está logado
     public function __construct(){
        $this->loggedUser = LoginHandler::checkLogin();
        if($this->loggedUser === false){
            $this->redirect('/login');
        }
    }
   
    public function index() {
        //$searchTerm = filter_input(INPUT_GET, 'search');
        $searchTerm = filter_input(INPUT_GET, 'search');

        if(empty($searchTerm)){
            $this->redirect('/');
        }
        $users = LoginHandler::searchUser($searchTerm);
        
        

        $this->render('search',[
            'loggedUser' => $this->loggedUser,
            'searchTerm' => $searchTerm,
            'users'=>$users
        ]);
    }
  
   

}