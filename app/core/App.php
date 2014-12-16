<?php

class App {

    protected $url;
    protected $routes = [];
    protected $currentController = DEFAULT_CONTROLLER;
    protected $currentAction = DEFAULT_ACTION;

    /**
     * @return \App
     */
    public function __construct(){

    }

    /**
     * @access public
     * @return void
     */
    public function run(){
        $this->url = $this->parseUrl();
        $route = $this->getRoute();

        if (!empty($this->routes[$route]['isOauthRequired'])){
            if(!Security::isUserLoggedIn()){
                Helper::redirectTo(WEB.DEFAULT_ROUTE);
            }
        }

        if (!empty($this->routes[$route]['controller'])){
            $controller =$this->routes[$route]['controller'];
            $this->setController($controller);
        }

        $this->loadControllerFile();
        $this->initControllerClass();
        $this->runControllerAction($this->getAction(), $this->getParams());
    }

    /**
     * @access private
     * @param string $controller
     * @return void
     */
    private function setController($controller){
        if (!empty($controller)) {
            if(file_exists(CONTROLLER_PATH.$controller.'Controller.php')) {
                $this->currentController = $controller.'Controller';
            }
        }
    }

    /**
     * @desc Load controller class file.
     *
     * @access private
     * @return void
     */
    private function loadControllerFile(){
        require_once CONTROLLER_PATH.$this->currentController.'.php';
    }

    /**
     * @desc Create a new instance of controller class and run its  __construct method.
     *
     * @access private
     * @return void
     */
    private function initControllerClass(){
        if (class_exists($this->currentController)){
            $this->currentController = new $this->currentController();
        }
    }

    /**
     * @desc This method checks if the action exists then set $currentAction.
     *
     * @access private
     * @param string $action
     * @param array $params
     * @return void
     */
    private function runControllerAction($action = "index", $params = []){
        if (!empty($action) && method_exists($this->currentController, $action.'Action')){
            $this->currentAction = $action.'Action';
        }
        call_user_func_array([$this->currentController, $this->currentAction], $params);
    }

    /**
     * @desc First checking if url is given then parsing.
     *
     * @access private
     * @return array
     */
    private function parseUrl(){
        return $url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [];
    }

    /**
     * @access private
     * @return string
     */
    private function getRoute(){
        return $this->unsetArray(0);
    }

    /**
     * @access private
     * @return string
     */
    private function getAction(){
        return $this->unsetArray(1);
    }

    /**
     * @desc This is a helper method which getRoute and getAction use.
     *
     * @access private
     * @param $index
     * @return string
     */
    private function unsetArray($index){
        if (sizeof($this->url) >= $index){
            if (!empty($this->url[$index])){
                $return = $this->url[$index];
                unset($this->url[$index]);
                return $return;
            }
        }
    }

    /**
     * @access private
     * @return array
     */
    private function getParams(){
       return $this->url ? array_values($this->url) : [];
    }

    /**
     * @access public
     * @param string $routeName
     * @param string $controllerName
     * @param bool $isOauthRequired
     * @return void
     */
    public function addRoute($routeName, $controllerName, $isOauthRequired){
        $this->routes[$routeName] = array('controller' => $controllerName, 'isOauthRequired' => $isOauthRequired);
    }

    /**
     * @access public
     * @return array
     */
    public function getRoutes(){
        return $this->routes;
    }
} 