<?php

class DetailController extends Controller {

    public function __construct(){
        parent::__construct('detail');
    }

    public function indexAction(){
        if ($this->isAJAX()){
            $status = 400;
            $data = array('error' => 'bad_request');
            if ($this->isRequestMethod('DELETE')){
                $request = json_decode(file_get_contents('php://input'));
                if(
                    filter_var($request->{'_csrf_token_detail'}, FILTER_SANITIZE_STRING) &&
                    filter_var($request->{'_id'}, FILTER_VALIDATE_INT)
                ) {
                    $csrf_token_detail = htmlspecialchars($request->{'_csrf_token_detail'} , ENT_QUOTES);
                    $id = htmlspecialchars($request->{'_id'} , ENT_QUOTES);
                    if ($csrf_token_detail == hash('sha256', Security::getCSRFToken('csrf_token_detail'))){
                        $status = 200;
                        $data = array('error' => 'invalid_user');
                        if ($id == Security::getUserId()) {
                            $user = $this->loadModel('User');
                            $userId = Session::Get('id');
                            $user->Id = $userId;
                            if($userId == $user->destroy()){
                                Security::destroyCSRFToken('csrf_token_detail');
                                Security::loggedOut();
                                $status = 204;
                                $data = array('success' => true);
                            }
                        }
                    }
                }
            }
            http_response_code($status);
            echo json_encode($data);
        } else {
            $this->loadView('Detail/index', 'Detail', [STYLES.'detail.css'], [SCRIPTS.'user.js', SCRIPTS.'detail.js'], ['id'=> Security::getUserId(), 'csrf_token_detail' => Security::generateCSRFToken('csrf_token_detail')]);
        }
    }
} 