<?php

class User_model extends CI_Model {
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

    /**
     * Login kontrolü yapýlýyor
     * @param phone string
     * @param password string
     * */

     public function userAuthentication($phone=false,$password=false){

       if(!$phone || !$password) {
            return;
        }

       $where=array(
        'username' =>$phone
        );

       $this->db->select('*');
       $this->db->from('uyeler');
       $this->db->where($where);
       $this->db->where('sifre',md5($password));
       $que = $this->db->get();
       $query=$que->row();


        if(count($query)>0){
            return $query; 
        }
 
        return false;

     }

 

         /*

     * Tüm Üyeler Çekiliyor

     * 

     * @params $limit int

     * @params $start int

     * */

     

     public function getAllUsers($limit, $start) {


        $this->db->limit($limit, $start);
        $query = $this->db->get("users");

        
         if ($query->num_rows() > 0) {
            return $query->result();
         }


        return array();

     }

	public function getFriends($ids){
		$this->db->from('uyeler');
		
		if(empty($ids))
			$this->db->or_where('uye_id','0');
		
		foreach($ids as $v){
			$this->db->or_where('uye_id',$v->friend_id);
		}
		$result=$this->db->get()->result();
		if(count($result>0)){
			return $result;
		} else {
			return false;
		}
	}

     /**
      * 
      * Üyelerin sayısı alınıyor
      * 
      * */

      

      public function getAllCount() {
        return $this->db->count_all("users");
      }

      

      /**

       * Kullanýcý Adý Kontrolü
       * @param $username string
       * */

       public function checkUsername($username) {
        $this->db->where('username',$username);
        return $this->db->count_all_results("users");

       }

       

      //Forget Password 

      public function checkUser($phone=false){
          if(!$phone) {
              return;

          }

          $sql = "select * from users WHERE phone ='".$phone."' and status = '1'"; 

          $query = $this->db->query($sql);
          if($query->num_rows() > 0) {
              return $query->result(); 

          }

          return false;
       }

       /**

       * E-Mail Kontrolü

       * @param $username string

       * */


       public function checkEmail_users($username) {

        $this->db->where('username',$username);
        return $this->db->count_all_results("uyeler");

       }



       /**
      * Üye Silme Islemi
      * 
      * @params $id int
      * */

      public function deleteUser($id=false) {

        if(!$id) {
            return;
        }

        $this->db->where('id', $id);
        $this->db->delete('users'); 
      }

      

      /**
       * Üye detayi bulunuyor
       * 
       * @param $id int
       * */

       

       public function getUser($id){

        $this->db->where('uye_id',$id);
        return $this->db->get('uyeler')->row();

       }

	   public function getUsername($id){

        $this->db->where('uye_id',$id);
        return $this->db->get('tb_uye')->row();

       }

       
       


}





?>

