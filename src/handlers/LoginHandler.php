<?php 
namespace src\handlers;
use \src\models\User;


class LoginHandler{


    //VERIFICA SE O USUÁRIO ESTÁ LOGADO E GUARDA OS DADOS NA SESSION
    public static function checkLogin(){
        //pegando o token e conferindo se ele existe
        if(!empty($_SESSION['token'])){
            //guardando token de acesso numa variável se ele existe
            $token =  $_SESSION['token'];
            //verificação 
            $data = User::select()->where('token', $token)->one();//execute()
            //se achar os dados
            if(count($data) > 0){
                $loggedUser = new User();
                $loggedUser->id = $data['id'];
                $loggedUser->email = $data['email'];
                $loggedUser->name = $data['name']; 
                $loggedUser->permissao = $data['permissao'];
                $loggedUser->status = $data['status']; 
                return $loggedUser;
            } 
        }
        return false;
    }
    public static function exit(){
        $token = false;

    }
        
    
    //VERIFICA SE OS DADOS PARA LOGAR ESTÃO CORRETOS
    public static function verifyLogin($email, $password){ 
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
    //VERIFICA SE O EMAIL JÁ EXISTE NO BANCO
    public static function emailExists($email){
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }
    
    
    //ADICIONA NOVOS USUÁRIOS
    public static function addUser($name, $email, $password, $cpf, $status, $permissao){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        //gera token de login
        $token = md5(time().rand(0,9999).time());

        User::insert([
            'email' => $email,
            'password' => $hash,
            'name' => $name,
            'cpf' => $cpf,
            'token' => $token,
            'status' => $status,
            'permissao'=> $permissao
            
        ])->execute();
        return $token;
    }
    public static function searchUser($term){
        $users=[];
        $data = User::select()->where('name', 'like', '%'.$term.'%')->get();

        if($data){
            foreach($data as $user){
                $newUser = new User();
                $newUser-> id = $user['id'];
                $newUser-> name = $user['name'];
                $newUser-> email = $user['email'];
                
                $users[] = $newUser;
            }
        }
        return $users;
    } 
}