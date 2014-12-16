<?php

class Model extends Table {

    protected $model_name;

    /**
     * @param string $model_name
     * @param string $table_name
     * @return \Model
     */
    public function __construct($model_name, $table_name){
        $this->model_name = $model_name;
        $this->table_name = $table_name;
        $this->db_conn_params = unserialize(DB_CONN_PARAMS);
    }

    /**
     * @access protected
     * @param array $fields
     * @param array $where
     * @return array
     */
    protected function fetch($fields = array(), $where = array()){
        $this->_select($this->table_name, $fields, $where);
        return $this->result;
    }

    /**
     * @access private
     * @param array $fields_values
     * @return int
     */
    private function create($fields_values = array()){
        $this->_insert($this->table_name, $fields_values);
        return $this->insert_id;
    }

    /**
     * @access public
     * @return array
     */
    public function read(){
        return $this->fetch(array('*'), array('id' => $this->Id));
    }

    /**
     * @access public
     * @param array $fields_values
     * @return int
     */
    private function update($fields_values = array()){
        $this->_update($this->table_name, $fields_values, array('id' => $this->Id));
        return $this->affected_rows;
    }

    /**
     * @access public
     * @param array $fields_values
     * @return int
     */
    public function save($fields_values = array()){
        if ($this->Id == 0){
            return $this->Create($fields_values);
        } else if ($this->Id > 0){
            $affected_rows = $this->Update($fields_values);
            return ($affected_rows == 1) ? $this->Id : 0;
        }
    }

    /**
     * @access public
     * @return int
     */
    public function delete(){
        $this->_delete($this->table_name, array('id' => $this->Id));
        return $this->affected_rows;
    }

    /**
     * @access public
     * @param array $columns
     * @return int
     */
    public function deleteByColumn($columns = array()){
        $this->_delete($this->table_name, $columns);
        return $this->affected_rows;
    }

    /**
     * @access public
     * @return int
     */
    public function destroy(){
        $affected_rows = $this->Delete();
        return ($affected_rows == 1) ? $this->Id : 0;
    }

    /**
     * @access public
     * @param array $columns
     * @return array
     */
    public function findByColumn($columns = array()){
        return $this->fetch(array('*'), $columns);
    }

    /**
     * @access protected
     * @return array
     */
    protected function findAll(){
        return $this->fetch(array('*'));
    }

    /**
     * @access protected
     * @param int $id
     * @return array
     */
    protected function findById($id){
        return $this->fetch(array('*'), array('id' => $id));
    }
}