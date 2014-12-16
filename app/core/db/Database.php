<?php

class Database {

    /**
     * @var mysqli
     */
    private $link;

    /**
     * @var string
     * @var int
     * @var bool
     */
    private $result;
    private $fetchMode = MYSQLI_NUM;
    private $fetchModes = array(MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH);
    private $query_result_func;

    /**
     * @access public
     * @param array $db_conn_params Required connection params.
     * @param string $charset
     * @return \Database
     */
    public function  __construct($db_conn_params, $charset = 'utf8') {
        $this->link = new mysqli($db_conn_params['host'], $db_conn_params['username'], $db_conn_params['password'], $db_conn_params['db']);
        $this->link->set_charset($charset);
        if ($this->link->connect_errno) die('Unable to connect to database');
        /*
            try {
                $this->link = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
                $this->link->set_charset( "utf8" );
            } catch ( Exception $e ) {
                die( 'Unable to connect to database' );
            }
        */
    }

    /**
     * @access public
     * @param none
     * @return void
     */
    public function __destruct() {
        if($this->link) $this->disconnect();
    }

    /**
     * @access public
     * @param mixed $data
     * @param int $quote_style
     * @param string $charset
     * @param bool $double_encode
     * @return mixed $data
     */
    public function filter($data, $quote_style = ENT_QUOTES, $charset = 'UTF-8', $double_encode = false) {
        if(!is_array($data)) {
            $data = $this->link->real_escape_string($data);
            $data = trim(htmlentities($data, $quote_style, $charset, $double_encode));
        } else {
            $data = array_map(array($this, 'filter'), $data);
        }
        return $data;
    }
    /**
     * @access public
     * @param mixed $data
     * @return mixed $data
     */
    public function escape($data){
        if(!is_array($data )){
            $data = $this->link->real_escape_string($data);
        } else {
            $data = array_map( array($this, 'escape'), $data);
        }
        return $data;
    }

    /**
     * @desc Optionally set the return mode. The mode: 0 for MYSQLI_NUM, 1 for MYSQLI_ASSOC, 2 for MYSQLI_BOTH
     *
     * @access public
     * @param int $type
     * @return void
     */
    public function set_fetchMode($type) {
        $this->fetchMode = $this->fetchModes[$type];
    }

    /**
     * @dec Prepare only a single query
     *
     * @access public
     * @param string $query
     * @return bool
     */
    public function single_query($query) {
        $this->result = $this->link->query($query); //MYSQLI_USE_RESULT
        return $this->set_query_result_func('single_query_result');
    }

    /**
     * @dec Prepare only multi query
     *
     * @access public
     * @param string $multi_query
     * @return bool
     */
    public function multi_query($multi_query){
        $this->result = $this->link->multi_query($multi_query);
        return $this->set_query_result_func('multi_query_result');
    }

    /**
     * @desc Prepare both single and multi queries
     *
     * @access public
     * @param string $query
     * @param string $multi_query_delimiter
     * @return bool
     */
    public function query($query, $multi_query_delimiter = ';'){
        if (sizeof(explode($multi_query_delimiter, $query)) > 1){
            return $this->multi_query($query);
        } else {
            return $this->single_query($query);
        }
    }

    /**
     * @desc Get the result
     *
     * @access public
     * @return mixed
     */
    public function result(){
       return call_user_func([$this, $this->query_result_func]);
    }

    /**
     * @desc Get only single query result
     *
     * @access public
     * @return mixed
     */
    public function single_query_result(){
        $rows = [];
        while($row = $this->result->fetch_array($this->fetchMode)){
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * @desc Get only multi query result
     *
     * @access public
     * @return mixed
     */
    public function multi_query_result(){
        $data = array();
        do {
            if ($this->result = $this->link->store_result()) {
                $data[] = $this->result->fetch_all($this->fetchMode);
                $this->result->free();
            }
        } while($this->link->more_results() && $this->link->next_result());
        return $data;
    }

    /**
     * @desc Check if there is a result then prepare(set) a proper return function
     *
     * @access private
     * @param string $query_result_func
     * @return bool
     */
    private function set_query_result_func($query_result_func){
        if ($this->result == true){
            $this->query_result_func = $query_result_func;
            return true;
        }
        return false;
    }

    /**
     * @access public
     * @param string $table_name
     * @param array $fields_values
     * @return bool
     */
    public function insert($table_name, $fields_values = array()){
        $fields = array();
        $values = array();
        foreach ($fields_values as $field => $value) {
            $fields[] = $this->filter($field);
            $values[] = (!is_numeric ($value)) ? "'".$this->escape($value)."'" : $value;
        }
        $fields = implode(',', $fields); // vs $fields = '`' . implode( '`,`', $fields ) . '`';
        $values = implode(',', $values);
        $query = "INSERT INTO {$table_name} ({$fields}) VALUES ({$values})";
        return $this->single_query($query);
    }

    /**
     * @access public
     * @param string $table_name
     * @param array $fields
     * @param array $where
     * @return bool
     */
    public function select($table_name, $fields = array(), $where = array()) {
        $columns = array();
        foreach ($fields as $field) {
            $columns[] = $this->filter($field);
        }
        $query = 'SELECT '. implode(',', $columns).' FROM '.$table_name;
        if(!empty($where) && sizeof($where) > 0) {
            $clause = array();
            foreach($where as $field => $value) {
                $clause[] = $this->filter($field).'='.((!is_numeric ($value)) ? "'".$this->escape($value)."'" : $value);
            }
            $query .= ' WHERE '. implode(' AND ', $clause);
        }
        return $this->single_query($query);
    }

    /**
     * @access public
     * @param string $table_name
     * @param array $fields_values
     * @param array $where
     * @return bool
     */
    public function update($table_name, $fields_values = array(), $where = array()) {
        $set = array();
        foreach ($fields_values as $field => $value) {
            $set[] = $this->filter($field).'='.((!is_numeric ($value)) ? "'".$this->escape($value)."'" : $value);
        }
        $query = "UPDATE {$table_name} SET ".implode(',', $set);
        if(!empty($where)) {
            $clause = array();
            foreach($where as $field => $value) {
                $clause[] = $this->filter($field).'='.((!is_numeric ($value)) ? "'".$this->escape($value)."'" : $value);
            }
            $query .= ' WHERE '. implode(' AND ', $clause);
        }
        return $this->single_query($query);
    }

    /**
     * @access public
     * @param string $table_name
     * @param array $where
     * @return bool
     */
    public function delete($table_name, $where = array()) {
        $query = "DELETE FROM {$table_name}";
        if(!empty($where)) {
            $clause = array();
            foreach($where as $field => $value) {
                $clause[] = $this->filter($field).'='.((!is_numeric ($value)) ? "'".$this->escape($value)."'" : $value);
            }
            $query .= ' WHERE '. implode(' AND ', $clause);
        }
        return $this->single_query($query);
    }

    /**
     * @desc Get last auto-incrementing ID associated with an insertion
     *
     * @access public
     * @param none
     * @return int
     */
    public function insert_id() {
        return $this->link->insert_id;
    }

    /**
     * @desc Returns the number of rows affected by a given query
     *
     * @access public
     * @param none
     * @return int
     */
    public function affected_rows() {
        return $this->link->affected_rows;
    }

    /**
     * @desc Returns the error code for the most recent function call
     *
     * @access public
     * @param none
     * @return int
     */
    public function errno() {
        return $this->link->errno;
    }

    /**
     * @access public
     * @param none
     * @return void
     */
    public function disconnect() {
        $this->link->close();
    }
} 