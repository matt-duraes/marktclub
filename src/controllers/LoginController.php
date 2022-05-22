<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;
use \src\models\User;

class LoginController extends Controller {
    public function signin(){
        $flash = '';
        if(!empty($_SESSION['flash'])){
                $flash = $_SESSION['flash'];
                $_SESSION['flash'] = '';
        }
        $this->render('signin',[
            'flash' => $flash
        ]);
    }
   public function signinAction(){
        $email = filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        
        if($email && $password){
            $token = LoginHandler::verifyLogin($email, $password);
        

            if($token){
                $_SESSION['token'] = $token;
                $this->redirect('/');
            } else{
                $_SESSION['flash'] = 'Email ou senha incorretos';
                    $this->redirect('/login');
                }
            
        } else {    
        // $_SESSION['flash'] ='Digite os campos de e-mail e senha';
            $this->redirect('/login');
        }
    }
   public function signup(){
        $flash = '';
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('signup',[
            'flash' => $flash
        ]);
   }

    public function signupAction(){
    
       $email= filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);
       $password = filter_input(INPUT_POST, 'password');
       $name = filter_input(INPUT_POST, 'name');
       $cpf = filter_input(INPUT_POST, 'cpf');

       if($name && $email && $password && $cpf){
            $status = $_POST['status'];
            $permissao = isset($_POST['permissao']) ? $_POST['permissao'] : null;
            $permissoes = $_POST['permissao'];
            if($status == 'ativo'){
                $status = 1;  
            } else {
                $status = 0;
            }
            $teste = implode("," , $permissao);
            $permissao = $teste;

            if(LoginHandler::emailExists($email) === false){
                $token = LoginHandler::addUser($name, $email, $password, $cpf, $status, $permissao);
                $_SESSION['token'] = $token;
                $this->redirect('/');
            } else {
                $_SESSION['flash'] = 'Email já cadastrado!';
                $this->redirect('/cadastro');
            }
        }else{   
            $this->redirect('/cadastro');
        }
    }
    public function edit($args){
        $user= User::select()->where('id', $args['id'])->one();
        print_r($user);
        $this->render('edit',[
            'user' => $user
        ]);
    }
    public function editAction($args){
       $email= filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);
       $name = filter_input(INPUT_POST, 'name');
       $cpf = filter_input(INPUT_POST, 'cpf');

       if($name && $email &&  $cpf){
        $status = $_POST['status'];
        $permissao = isset($_POST['permissao']) ? $_POST['permissao'] : null;
        $permissoes = $_POST['permissao'];
        if($status == 'ativo'){
            $status = 1;  
        } else {
            $status = 0;
        }
        $teste = implode("," , $permissao);
        $permissao = $teste;
        User::update()
            ->set('name', $name,)
            ->set('email',$email,)
            ->set('cpf',$cpf)
            ->set('permissao',$permissao)
            ->set('status',$status)
            ->where('id', $args['id'])
            ->execute();
            $this->redirect('/');
        }else{   
            $this->redirect('/usuario/'.$args['id'].'/editar');
        }
    }
    public function del($args){
        User::delete()->where('id', $args['id'])->execute();  
        $this->redirect('/');
    }
   
   
}