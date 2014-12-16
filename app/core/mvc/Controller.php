<?php

class Controller {

    /**
     * @var string
     */
    protected $name = "";

    /**
     * @access public
     * @param string $name
     * @return \Controller
     */
    public function __construct($name){
        $this->name = $name;
    }

    /**
     * @access public
     * @param string $model_name
     * @return object
     */
    public function loadModel($model_name){
        if(file_exists(MODEL_PATH.$model_name.'.php')) {
            require_once MODEL_PATH.$model_name.'.php';
            if (class_exists($model_name)){
                $model = $model_name;
                return new $model();
            }
        }
    }

    /**
     * @access public
     * @param string $route_template
     * @param array $styles
     * @param array $scripts
     * @param array $data
     * @return void
     */
    public function loadView($route_template, $title, array $styles, array $scripts, array $data = []){
        $view = new View($route_template, $title);
        $view->addStyles($styles);
        $view->addScripts($scripts);
        $view->addData($data);
        $view->render();
    }

    /**
     * @access public
     * @param string $method_type;
     * @return bool
     */
    public function isRequestMethod($method_type){
        return ($this->getRequestMethod() === $method_type) ? true : false;
    }

    /**
     * @access public
     * @return string
     */
    public function getRequestMethod(){
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @access public
     * @return bool
     */
    public static function isAJAX() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
} 