<?php

class Helper {

    /**
     * @access public
     * @param string $route
     * @return void
     */
    public static function redirectTo($route){
        header("Location: ".$route);
    }

} 