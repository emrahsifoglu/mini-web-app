<?php

class Security {

    /**
     * @access public
     * @return bool
     */
    public static function isUserLoggedIn(){
        Session::Start();
        return (Session::Get('id') == 0) ? false : true;
    }
    /**
     * @access public
     * @return int
     */
    public static function getUserId(){
        Session::Start();
        return Session::Get('id');
    }

    /**
     * @access public
     * @param integer $id
     * @return bool
     */
    public static function loggedIn($id){
        Session::Start();
        Session::Set('id', $id);
    }

    /**
     * @access public
     * @return bool
     */
    public static function loggedOut(){
        Session::Start();
        Session::Set('id', 0);
        Session::Stop();
    }

    /**
     * @access public
     * @param string $name
     * @return mixed
     */
    public static function getCSRFToken($name){
        Session::Start();
        return Session::Get($name);
    }

    /**
     * @access public
     * @param string $name
     * @return void
     */
    public static function destroyCSRFToken($name){
        Session::Start();
        Session::Destroy($name);
    }

    /**
     * @access public
     * @param string $name
     * @param int $length
     * @return string
     */
    public static function generateCSRFToken($name, $length = 100){
        $token = self::random($length);
        Session::Start();
        Session::Set($name, $token);
        return hash('sha256', $token);
    }

    /**
     * @desc This function generates a random string using the linux random file for more entropy
     * @param int $length
     * @return string
     */
    private static function random($length) {
        $return = '';
        if (function_exists('openssl_random_pseudo_bytes')) {
            $byteLen = intval(($length / 2) + 1);
            $return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $length);
        } elseif (@is_readable('/dev/urandom')) {
            $f=fopen('/dev/urandom', 'r');
            $urandom=fread($f, $length);
            fclose($f);
            $return = '';
        }

        if (empty($return)) {
            for ($i=0; $i < $length; ++$i) {
                if (!isset($urandom)) {
                    if ($i%2==0) {
                        mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
                    }
                    $rand=48+mt_rand()%64;
                } else {
                    $rand=48+ord($urandom[$i])%64;
                }

                if ($rand>57)
                    $rand+=7;
                if ($rand>90)
                    $rand+=6;

                if ($rand==123) $rand=52;
                if ($rand==124) $rand=53;
                $return.=chr($rand);
            }
        }
        return $return;
    }
}