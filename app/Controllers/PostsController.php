<?php
namespace App\Controllers;

use Core\Container;
use Core\BaseController;
use Core\Redirect;
use Core\Session;
use Core\Validator;

class PostsController extends BaseController
{
    private $post;

    public function __construct()
    {
        parent::__construct();
        $this->post = Container::getModel('Post');
    }

    public function index(){
        if(Session::get('success')){
            $this->view->success = Session::get('success');
            Session::destroy('success');
        }
        if(Session::get('errors')){
            $this->view->errors = Session::get('errors');
            Session::destroy('errors');
        }
        $this->setPageTitle('Post');
        $this->view->posts = $this->post->All();
        return $this->loadView('Posts/index', 'layout');
    }

    public function show($id){
        $this->view->post = $this->post->findById($id);
        $this->setPageTitle("{$this->view->post->titulo}");
        return $this->loadView('Posts/show','layout');
    }
    public function create(){
        $this->setPageTitle('Novo Post');
        return $this->loadView('Posts/create','layout');
    }
    public function store($request){
        $data = [
          'titulo' => $request->post->titulo,
          'conteudo' => $request->post->conteudo
        ];

        if($this->post->create($data)){
            return Redirect::route('/posts', [
                'success' => ['Post criado com sucesso']
            ]);
        }else{
            return Redirect::route('/posts', [
                'errors' => ['Erro ao criar Post']
            ]);
        };
    }
    public function edit($id){
        if(Session::get('inputs')){
            $this->view->inputs = Session::get('inputs');
            Session::destroy('inputs');
        }
        if(Session::get('errors')){
            $this->view->errors = Session::get('errors');
            Session::destroy('errors');
        }
        $this->view->post = $this->post->findById($id);
        $this->setPageTitle("Editar post - {$this->view->post->titulo}");
        return $this->loadView("Posts/edit","layout");

    }

    public function  update($id,$request){
        $data = [
            'titulo' => $request->post->titulo,
            'conteudo' => $request->post->conteudo
        ];
        $rules = [
            'titulo' => 'required',
            'conteudo' => 'required',
        ];

        $validator = Validator::make($data,$rules);

        if($validator){
            return Redirect::route("/posts/{$id}/edit");
        }
        if($this->post->update($data, $id)){
           return Redirect::route('/posts', [
               'success' => ['Post atualizado com sucesso']
           ]);
        }else{
            return Redirect::route('/posts', [
                'errors' => ['Erro ao atualizar o post','Tente Novamente']
            ]);
        };
    }

    public function delete($id){
        if($this->post->delete($id)){
           return Redirect::route('/posts',[
               'success' => ['Post deletado com sucesso']
           ]);
        }else{
            return Redirect::route('/posts', [
                'errors' => ['Erro ao deletar post']
            ]);
        };
    }
}

