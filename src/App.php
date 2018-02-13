<?php namespace Web\Engine;

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Request;
use Web\Engine\DB;

class App{
    private $request;
    private $frame;
    private $real_routes;


    public function __construct($config) {
        $this->real_routes = $this->getRealRoutes($config);
        $this->request = Request::createFromGlobals();
        $this->frame = new LittleCore($this->real_routes);
    }

    private function getRealRoutes($_config){
        $real_routes = new Routing\RouteCollection();              // Симфони роутинг Создание коллекции
        $_config_arr = json_decode($_config, true);
        foreach ($_config_arr as $key => $value){
            if ($key == 'urls'){
                $routes_config = $value;
                foreach ($routes_config as $routs_key => $routs_value){
                    $name = $routs_key;
                    $path = $routs_value['path'];
                    $controller = $routs_value['controller'];
                    $real_routes->add($name, new Routing\Route($path,
                            array(
                                '_controller' => $controller,
                            )
                        )
                    );
                }
            } elseif ($key == 'db') {
                $db = DB::getInstance();
                $db->setDBConfig($value);
            }
        };
        return $real_routes;
    }
    public function run(){
        $this->frame->handle($this->request)->send();
    }
}
