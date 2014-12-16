<?php

class EmailController extends Controller {

    public function __construct(){
        parent::__construct('email');
        if (!$this->isAJAX())
            Helper::redirectTo(WEB.DEFAULT_ROUTE);
    }

    public function indexAction(){
        if ($this->isAJAX() && $this->isRequestMethod('POST')){
            $status = 400;
            $data = array('error' => 'bad_request');
            if (filter_has_var(INPUT_POST ,'username') && filter_has_var(INPUT_POST, 'email')){
                $username = $_POST['username'];
                $to = $_POST['email'];
                if (filter_var($to, FILTER_VALIDATE_EMAIL) && filter_var($username, FILTER_SANITIZE_STRING) ){
                    $status = 200;
                    $data = array("success" => true);
                    $message = file_get_contents(VIEW_PATH.'Email/register.html');
                    $message = str_replace('[[username]]', $username, $message);
                    $message = str_replace('[[link]]', 'http://'.$_SERVER['HTTP_HOST'].WEB.'detail', $message);
                    $email_params = unserialize(EMAIL_PARAMS);
                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->Host = $email_params['host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $email_params['username'];
                    $mail->Password = $email_params['password'];
                    $mail->SetFrom($email_params['from'], 'Mini Web App Admin');
                    $mail->AddAddress($to);
                    $mail->Subject = 'Your account information';
                    $mail->MsgHTML($message);
                    if(!$mail->Send()){
                        $status = 400;
                        $error = $mail->ErrorInfo;
                        $data = array('error' => $error);
                    }
                }
            }
            http_response_code($status);
            echo json_encode($data);
        } else {
            Helper::redirectTo(WEB.DEFAULT_ROUTE);
        }
    }
} 