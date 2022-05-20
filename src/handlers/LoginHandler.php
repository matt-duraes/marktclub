<?php 
namespace src\handlers;
use \src\models\User;

class LoginHandler{
    //ver se está logado
    public static function checkLogin(){
            //pegando o token e conferindo se ele existe
        if(!empty($_SESSION['token'])){
            //guardando token de acesso numa variável se ele existe
            $token =  $_SESSION['token'];
            //verificação 
            $data = User::select()->where('token', $token)->one();//execute()
            //se achar os dados
            if(count($data) > 0){
                //retorna a base do usuário
                /*  com get e set
                $loggedUser->setId($data['id']);s
                $loggedUser->setEmail($data['email']);
                $loggedUser->setName($data['name']);
                */
                $loggedUser = new User();
                $loggedUser->id = $data['id'];
                $loggedUser->email = $data['email'];
                $loggedUser->name = $data['name'];  



                return $loggedUser;
            } 
        }
        return false;
    }
    public static function verifyLogin($email, $password){ 
        //verifica se email enviado é msm que consta no banco
        $user = User::select()->where('email', $email)->one();

        if($user){
            if(password_verify($password, $user['password'])){
               $token = md5(time().rand(0,9999).time());

               User::update()
                    ->set('token',$token)
                    ->where('email', $email)
                ->execute();
               return $token;
            }
        }
        return false;
        
    }
    public static function emailExists($email){
        //verifica se email enviado é msm que consta no banco
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    public static function addUser($name, $email, $password, $cpf){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        //gera token de login
        $token = md5(time().rand(0,9999).time());

        User::insert([
            'email' => $email,
            'password' => $hash,
            'name' => $name,
            'cpf' => $cpf,
            'token' => $token
        ])->execute();
        return $token;
    }
}