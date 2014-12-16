<?php

class HomeController extends Controller {

    public function __construct(){
        parent::__construct('home');
    }

    public function indexAction(){
        $this->loadView('Home/index', 'Home', [STYLES.'home.css'], [SCRIPTS.'home.js']);
    }

}