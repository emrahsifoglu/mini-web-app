<?php

class RegisterController extends Controller {

    public function __construct(){
        parent::__construct('register');
        if (Security::isUserLoggedIn())
            Helper::redirectTo(WEB.'detail');
    }

    public function indexAction(){
        $title = "Register";
        $styles = [BOWER.'bootstrap-3-datepicker/css/datepicker.css', STYLES.'register.css'];
        $scripts = [BOWER.'bootstrap-3-datepicker/js/bootstrap-datepicker.js', SCRIPTS.'user.js', SCRIPTS.'register.js'];
        $this->loadView('Register/index', $title, $styles, $scripts, ['csrf_token_register' => Security::generateCSRFToken('csrf_token_register')]);
    }

    /**
     * @return void
     */
    public function createAction() {
        if ($this->isAJAX() && $this->isRequestMethod('POST')){
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_register'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_username'}, FILTER_VALIDATE_REGEXP,
                    array("options"=>array("regexp"=>'/^[a-zA-Z0-9]{3,15}$/'))) &&
                filter_var($request->{'_firstname'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_lastname'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_password'},  FILTER_VALIDATE_REGEXP,
                    array("options"=>array("regexp"=>'/^[a-zA-Z0-9]{6,20}$/'))) &&
                filter_var($request->{'_email'}, FILTER_VALIDATE_EMAIL) &&
                filter_var($request->{'_birthday'}, FILTER_VALIDATE_REGEXP,
                    array("options"=>array("regexp"=>'/^([0-9]{2})\)?[-. ]?([0-9]{2})[-. ]?([0-9]{4})$/')))){

                $status = 400;
                $data = array("error" => 'bad_request');
                $csrf_token_register = htmlspecialchars($request->{'_csrf_token_register'}, ENT_QUOTES);
                if ($csrf_token_register == hash('sha256', Security::getCSRFToken('csrf_token_register'))){
                    $username = htmlspecialchars($request->{'_username'} , ENT_QUOTES);
                    $firstname = htmlspecialchars($request->{'_firstname'}, ENT_QUOTES);
                    $lastname = htmlspecialchars($request->{'_lastname'}, ENT_QUOTES);
                    $password = htmlspecialchars($request->{'_password'}, ENT_QUOTES);
                    $email = htmlspecialchars($request->{'_email'}, ENT_QUOTES);
                    $birthday = htmlspecialchars($request->{'_birthday'}, ENT_QUOTES);
                    $user = $this->loadModel('User');
                    $user->Username = $username;
                    $user->Password = $password;
                    $status = 409;
                    $data = array('error' => 'username_is_taken');
                    if (!$user->isUsernameTaken()){
                        $id = $user->Save(array(
                                'username' => $username,
                                'firstname' => $firstname,
                                'lastname' => $lastname,
                                'password' =>  $user->Password,
                                'email' => $email,
                                'birthday' => $birthday
                            ));
                        $status = 201;
                        $data = array('id' => $id, 'email' => $email);
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