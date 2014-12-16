<?php

abstract class Table  {

    /**
     * @var int
     */
    public $Id = 0;

    /**
     * @var string
     */
    protected $table_name = '';

    /**
     * @var int
     */
    protected $errno = 0;

    /**
     * @var int
     */
    protected $insert_id = 0;

    /**
     * @var int
     */
    protected $affected_rows = 0;

    /**
     * @var int
     */
    protected $fetchMode = 0;

    /**
     * @var array
     */
    protected $result = array();

    /**
     * @var array
     */
    protected $db_conn_params = array();

    /**
     * @access protected
     * @param string $table_name
     * @param array $fields
     * @param array $where
     * @return void
     */
    protected function _select($table_name, $fields = array('*'), $where = array()){
        $database = new Database($this->db_conn_params);
        if ($database->select($table_name, $fields, $where)){
            $database->set_fetchMode($this->fetchMode);
            $this->result = $database->single_query_result();
        } else {
            $this->errno = $database->errno();
        }
    }

    /**
     * @access protected
     * @param string $table_name
     * @param array $fields_values
     * @return void
     */
    protected function _insert($table_name, $fields_values = array()) {
        $database = new Database($this->db_conn_params);
        if ($database->insert($table_name, $fields_values)){
            $this->insert_id = $database->insert_id();
        } else {
            $this->errno = $database->errno();
        }
    }

    /**
     * @access protected
     * @param string $table_name
     * @param array $fields_values
     * @param array $where
     * @return void
     */
    protected function _update($table_name, $fields_values = array(), $where = array()){
        $database = new Database($this->db_conn_params);
        if ($database->update($table_name, $fields_values, $where)){
            $this->affected_rows = $database->affected_rows();
        } else {
            $this->errno = $database->errno();
        }
    }

    /**
     * @access protected
     * @param string $table_name
     * @param array $where
     * @return void
     */
    protected function _delete($table_name, $where = array()){
        $database = new Database($this->db_conn_params);
        if ($database->delete($table_name, $where)){
            $this->affected_rows = $database->affected_rows();
        } else {
            $this->errno = $database->errno();
        }
    }
}