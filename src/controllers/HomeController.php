<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;
use \src\models\User;

class HomeController extends Controller {
    private $loggedUser;
     //conferir se está logado
     public function __construct(){
        $this->loggedUser = LoginHandler::checkLogin();
        if($this->loggedUser === false){
            $this->redirect('/login');
        }
    }
   
    public function index() {
        // $offset =  $_GET['p'];
        $limit = 4;
        $offset =  0;
      
        
        $total = User::select()->count();
        $pages = ceil(($total / $limit));
        
         $atual = 1;
         if(!empty($_GET['p'])){
             $atual = intval($_GET['p']);
         }
        $offset = ($atual * $limit)-$limit;

        $user = User::select()->limit($offset, $limit)->execute();

        $this->render('home', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'total' => $total,
            'limit' => $limit,
            'pages' => $pages,
            'offset' => $offset,
        ]);
    }
  
   

}