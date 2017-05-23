<?php

if (!defined('BASEPATH'))

    exit('No direct script access allowed');

class Task_model extends CI_Model{

     public function __construct()   {
      header('Access-Control-Allow-Origin: *');
      header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
      $method = $_SERVER['REQUEST_METHOD'];
      if($method == "OPTIONS") {
          die();
      }
       parent::__construct();

   }

  // kullanıcının almış olduğu task bilgisini geri  döndürür.
    function get_user_task($user_id){
       $this->db->select('b.task_id,b.status as user_task_status,b.added_date as kullanici_alis_tarihi,b.level');
       $this->db->from('tb_buy_task b');
       $this->db->where("b.user_id",$user_id);
       $this->db->group_by("b.id");
       $que=$this->db->get();
       $res=$que->result(); 


       $resarray = array();
       foreach ($res as $k => $v) {

        $this->db->select('a.*,s.sektor_slug as categori');
        $this->db->from('tb_task a');
        $this->db->join('tb_sektor s', 's.sektor_id= a.category_id', 'left');
        $this->db->where("a.id",$v->task_id);
        $this->db->group_by("a.id");
        $que=$this->db->get();
        $resq=$que->result();

         $resarray[$k]=$v;
         $resarray[$k]->task_info=$resq;
       }

        
       return $resarray;  
    }

 // kullanıcının vermiş olduğu task bilgisini geri  döndürür.   
    function get_send_user_task($user_id){
       $this->db->select('b.*');
       $this->db->from('tb_task b');
       $this->db->where("b.user_id",$user_id);
       $this->db->group_by("b.id");
       $que=$this->db->get();
       $res=$que->result(); 


       $resarray = array();
       foreach ($res as $k => $v) {

        $this->db->select('sektor_slug as categori');
        $this->db->from('tb_sektor');
        $this->db->where("sektor_id",$v->category_id);
        $this->db->group_by("sektor_id");
        $que=$this->db->get();
        $resq=$que->result();

         $resarray[$k]=$v;
         $resarray[$k]->task_info=$resq;
       }

        
       return $resarray;  
    }

     // Seçilen taskın user bilgisi ile geri döndürür 
    function get_user_task_info($task_id,$user_id){
       $this->db->select('b.*,u.ad,u.soyad,u.is_company');
       $this->db->from('tb_task b');
       $this->db->join('tb_uye u', 'u.uye_id= b.user_id', 'left');
       $this->db->where("b.id",$task_id);
       $this->db->group_by("b.id");
       $que=$this->db->get();
       $res=$que->row(); 


       $resarray = array();
       
        $this->db->select('*');
        $this->db->from('tb_send_task');
        $this->db->where("user_id",$user_id);
        $this->db->where("task_id",$task_id);
        $this->db->group_by("id");
        $que=$this->db->get();
        $resq=$que->row();

         $resarray[0]=$res;
         $resarray[0]->task_info=$resq;
      

        
       return $resarray; 
 
    }
}