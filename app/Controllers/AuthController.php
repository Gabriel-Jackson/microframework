<?php
namespace App\Controllers;

use App\Models\User;
use Core\BaseController;
use Core\Redirect;
use Core\Session;
use Core\Validator;
use Core\Bcrypt;

class AuthController extends BaseController
{
  private $user;

  public function __construct()
  {
      parent::__construct();
      $this->user = new User;
      $idUser = Session::get('idUser');

        if(isset($idUser) && !empty($idUser)){
            return Redirect::route('/');
        }
        
  }
  public function index($request){
    if(Session::get('success')){
      $this->view->success = Session::get('success');
      Session::destroy('success');
    }
    if(Session::get('errors')){
        $this->view->errors = Session::get('errors');
        Session::destroy('errors');
    }
    if(!isset($request->post->email)) {
      $this->setPageTitle('Login');
      return $this->loadView('Auth/login');
    }
    $email = $request->post->email;
    $usuario = $this->user->where('email',$email)->first();
 
    if(empty($usuario)){
      return Redirect::route('/login', [
        'errors' => ['Usuário não existe']
    ]);
    }
    $pass = $request->post->password;

    if(Bcrypt::check($pass,$usuario->password)){
      Session::set('idUser',$usuario->id);
      return Redirect::route('/');
    }
    return Redirect::route('/login', [
      'errors' => ["Senha Incorreta"]
  ]);
  }
  public function register($request){
    if(!isset($request->post->email)){
      $this->setPageTitle('Cadastro');
      return $this->loadView('Auth/register');
    }
    $usuario = $this->user->where('email',$request->post->email)->first();
    
    if(!empty($usuario)){
      return Redirect::route('/login', [
        'errors' => ['Usuário já existe']
      ]);
    }
    $data = [
      'name' => $request->post->nome,
      'email' => $request->post->email,
      'password' => Bcrypt::hash($request->post->password),
    ];
    if($this->user->create($data)){
        return Redirect::route('/login', [
            'success' => ['Você se Cadastrou com sucesso, agora efetue login']
        ]);
    }else{
        return Redirect::route('/posts', [
            'errors' => ['Ocorreu algum erro ao cadastrar, tente novamente']
        ]);
    };
  }
  
  public function logout(){
    Session::destroy('idUser');
    Redirect::route('/');
  }
}
?>