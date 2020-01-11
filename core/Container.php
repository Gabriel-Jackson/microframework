<?php
namespace Core;
class Container
{
    public static function newController($controller){
        $ctrlr = "App\\Controllers\\".$controller;
        return new $ctrlr;
    }
    public static function getModel($model){
        $objModel = "\\App\\Models\\".$model;
        return new $objModel(Database::getDataBase());
    }

    public static function pageNotFound(){
        if(file_exists(__DIR__."/../app/Views/404.phtml")){
            return require_once __DIR__."/../app/Views/404.phtml";
        }else{
            echo "Erro 404: Página não encontrada";
        }
    }
}

