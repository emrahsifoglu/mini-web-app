<?php

class LogoutController extends Controller {

    public function __construct(){
        parent::__construct('logout');
        if (!$this->isAJAX())
            Helper::redirectTo(WEB.DEFAULT_ROUTE);
    }

    public function indexAction(){
        if ($this->isAJAX() && $this->isRequestMethod('POST')){
            Security::loggedOut();
            http_response_code(200);
            echo json_encode(array('success'=>true));
        } else {
            Helper::redirectTo(WEB.DEFAULT_ROUTE);
        }
    }
} 