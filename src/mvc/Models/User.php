<?php

class User extends Model {

    public $Username;
    private $Password;
    private $encryptionKey = 'lkirwf897+22#bbtrm8814z5qq=498j5'; // 32 * 8 = 256 bit key
    private $encryptionIV = '741952hheeyy66#cs!9hjv887mxx7@8y'; // 32 * 8 = 256 bit iv

    /**
     * @return \User
     */
    public function __construct(){
        parent::__construct('User', 'users');
    }

    /**
     * @param string $property
     * @param string $value
     * @return void
     */
    public function __set($property, $value) {
        switch($property) {
            case 'Password':
                $this->Password = encryptRJ256($this->encryptionKey, $this->encryptionIV, $value);
                break;
        }
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function __get($property) {
        switch($property) {
            case 'Password':
                return $this->Password;
                break;
        }
    }

    /**
     * @desc This method returns user's id if the credential is found. In other cases, return value will be zero
     *
     * @return int
     */
    public function isAuthorized(){
        $result = $this->findByColumn(array('username' => $this->Username, 'password' => $this->Password));
        if (!empty($result)) return $result[0][0];
        return 0;
    }

    public function isUsernameTaken(){
        $result = $this->findByColumn(array('username' => $this->Username));
        if (!empty($result)) return $result[0][0];
        return 0;
    }
} 