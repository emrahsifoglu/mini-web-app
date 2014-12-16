<?php

class LoginController extends Controller {

    public function __construct(){
        parent::__construct('login');
        if (!$this->isAJAX())
            Helper::redirectTo(WEB.DEFAULT_ROUTE);
    }

    /**
     * @return void
     */
    public function indexAction(){
        if ($this->isAJAX() && $this->isRequestMethod('POST')){
            $status = 400;
            $data = array('error' => 'bad_request');
            if(filter_has_var(INPUT_POST, "_csrf_token_login") && filter_has_var(INPUT_POST, "_username") && filter_has_var(INPUT_POST, "_password")){
                $status = 403;
                $data = array('error'=>'bad_request');
                $csrf_token_login = htmlspecialchars($_POST['_csrf_token_login'], ENT_QUOTES);
                if ($csrf_token_login == hash('sha256', Security::getCSRFToken('csrf_token_login'))){
                    $status = 204;
                    $data = array('error'=>'no_content');
                    $username = htmlspecialchars($_POST['_username'], ENT_QUOTES);
                    $password = htmlspecialchars($_POST['_password'], ENT_QUOTES);
                    $user = $this->loadModel('User');
                    $user->Username = $username;
                    $user->Password = $password;
                    $id = $user->isAuthorized();
                    if ($id > 0) {
                        Security::loggedIn($id);
                        Security::destroyCSRFToken('csrf_token_login');
                        $status = 200;
                        $data = array('id' => $id);
                    }
                }
            }
            http_response_code($status);
            echo json_encode($data);
        } else {
            Helper::redirectTo(WEB.'register');
        }
    }
} 