<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbi_model extends CI_Model {

    function __construct(){

          header('Access-Control-Allow-Origin: *');
          header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
          $method = $_SERVER['REQUEST_METHOD'];
          if($method == "OPTIONS") {
              die();
          }

          
            parent::__construct();
    }


    
        public function query($table,$where='',$limit='',$order='',$select='',$ret_type='ob'){
            
                $this->db->from($table);
                
                if(is_array($where)){
                    $this->db->where($where);
                }
                if(ctype_digit($limit)){
                    $this->db->limit($limit);
                }

                if($order){
                    $this->db->order_by($order,"asc");
                }

                if($select){
                    $this->db->select($select);
                }

                if($ret_type=='ob'){                
                    $result=$this->db->get()->result();
                }
                if($ret_type=='arr'){                
                    $result=$this->db->get()->result_array();
                }

                

                if(count($result>0)){
                    return $result;
                } else {
                    return false;
                }
        }



        public function insert($table,$data){
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }

        public function delete($table,$where){
            if(!is_array($where)){
                return false;
            }
            $this->db->where($where);
            $this->db->delete($table);
            return $this->db->affected_rows();
        }

   
        public function update($table,$data,$where){
                 if(is_array($where)){
                    $this->db->where($where);
                } else {
                    return false;
                }
                 if(is_array($data)){
                    $this->db->update($table, $data);
                    return $this->db->affected_rows();        
                } else {
                    return false;
                }

        }   



        public function count($table,$where,$select=''){
                 if(is_array($where)){
                    $this->db->where($where);
                }
                if($select){
                    $this->db->select($select);
                }
                $this->db->from($table);
                $result=$this->db->get()->result();
                return count($result);
        }








}