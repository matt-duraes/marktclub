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
        $offset= 0;
        $limit = 4;
        $user = User::select()->limit($offset, $limit)->execute();
        $total = User::select()->count();
        $pages = ceil(($total / $limit));
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