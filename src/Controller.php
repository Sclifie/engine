<?php namespace Web\Engine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller {
    public function generateResponse($view, $template, $data=[]){
        // генерация ответа - html
        extract($data);
        require_once '../private/Views/' . $template; // выносить в настройки
        return new  Response('');
    }
    public function generateAjaxResponse($val){
        // генерация ответа на ajax Запрос
        return new Response($val);
    }

}