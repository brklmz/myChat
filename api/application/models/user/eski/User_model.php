<?php





class User_model extends CI_Model {



    function __construct()

    {

        parent::__construct();

    }

    

    

    /**

     * Login kontrolü yapýlýyor

     * 

     * @param phone string

     * @param password string

     * */

     

     public function userAuthentication($phone=false,$password=false)

     {

        if(!$phone || !$password)

        {

            return;

        }

       $where=array('u.phone' =>$phone,'u.email' =>$phone);
       $this->db->select('u.id,u.name,u.surname,email,u.status,u.phone,u.dealer_id,d.branch_name,d.status as dealer_status,dc.status as dealer_center_status');
       $this->db->from('users u');
       $this->db->join('dealer d', 'd.dealer_id= u.dealer_id', 'left');
       $this->db->join('dealer_center dc', 'dc.dealer_center_id= d.dealer_center_id', 'left');
       $this->db->or_where($where);
       $this->db->where('u.password',md5($password));
       $this->db->group_by('u.dealer_id');
       //$this->db->where("d.status","1");
       $que = $this->db->get();
       $query=$que->result();


        if(count($query)>0)

        {

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

     

     public function getAllUsers($limit, $start)

     {

        

        $this->db->limit($limit, $start);

        $query = $this->db->get("users");

        

         if ($query->num_rows() > 0) {

            

            return $query->result();

        }

        

        return array();

        

     }

     

     /**

      * 

      * Üyelerin sayýlarý alýnýypr

      * 

      * */

      

      public function getAllCount() {
        return $this->db->count_all("users");
      }

      

      

      /**

       * Kullanýcý Adý Kontrolü

       * @param $username string

       * */

       

       public function checkUsername($username)

       {

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

          



          if($query->num_rows() > 0)

          {

              return $query->result(); 

          }

          

           

          return false;

          

       }

       /**

       * E-Mail Kontrolü

       * @param $username string

       * */

       

    

       

       public function checkEmail_users($email,$phone)

       {

        $this->db->where_in('email',$email);

        $this->db->where_in('phone',$phone);

        return $this->db->count_all_results("users");

        

       }



       /**

      * Üye Silme Islemi

      * 

      * @params $id int

      * */

      

      public function deleteRow($id=false)

      {

        

        if(!$id)

        {

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

       

       public function getUser($id)

       {

        

        $this->db->where('id',$id);

        return $this->db->get('users')->row();

        

       }

       

       

     /**

     * Kullanici ve field value eslesmesi

     * 

     * @param $user_id int

     * @param $field_id int

     * */

     

     function getUserValueFromField($user_id,$field_id) {


       $a =  $this->db->get_where('user_to_fields',array('user_id' => $user_id,'field_id' => $field_id))->row();

        if($a)

        {

            return $a->value;

        }else

        {

            return false;

        }

     }

     

              /**

     * Tüm Üyeler Çekiliyor

     * 

     * @params $limit int

     * @params $start int

     * */

     

     public function getAllAddress($user_id,$limit, $start)

     {

        $this->db->where('user_id',$user_id);

        $this->db->limit($limit, $start);

        $query = $this->db->get("user_address");

        

         if ($query->num_rows() > 0) {

            

            return $query->result();

        }

        

        return array();

        

     }

     

     /**

      * 

      * Üyelerin sayýlarý alýnýypr

      * 

      * */

      

      public function getAddressCount($user_id) {

        

        $this->db->where('user_id',$user_id);
        $this->db->where('status',"1");
        return $this->db->count_all("user_address");

        

      }

      

       /**

      * Sehirler Çekiliyor

      * */     

      

      public function getAllCity()

      {

        

        

        $query = $this->db->get('city');

        

        if($query->num_rows() > 0)

        {

            return $query->result();

        }else

        {

            return false;

        }

        

        

      }

     

     

     /**

      * Sehir bilgileri aliniyor

      * */     

      

      public function getCity($city_id)

      {

        

        $this->db->where('id',$city_id);

        $query = $this->db->get('city');

        

        if($query->num_rows() > 0)

        {

            return $query->row();

        }else

        {

            return false;

        }

        

        

      }

      

      /**

      * Adres Silme Islemi

      * 

      * @params $id int

      * */

      

      public function deleteAddressRow($id=false)

      {

        

        if(!$id)

        {

            return;

        }

        

        $this->db->where('id', $id);

        $this->db->delete('user_address'); 

        

      }

      

      

      /**

       * Adres bilgileri getiriliyor

       * 

       * @param $id int

       * */

     

     public function getAddress($id=false)

     {

        

        if(!$id)

        {

            return;

        }

        $this->db->where('branch_id',$id);

        $query = $this->db->get('user_adress');

        

        if($query->num_rows() > 0)

        {

            return $query->result();

        }

        else

        {

            return false;

        }

        

        

     }

    public function checkedAddress($id=false)

     {

        $this->db->where('branch_id',$id);

        $query = $this->db->get('user_adress');

        $que=$query->result();

        

        return count($que);

        

     }

    public function checkinvoice($id=false)

     {

        $this->db->where('branch_id',$id);

        $query = $this->db->get('user_invoices');

        $que=$query->result();

        

        return count($que);

        

     }

     public function getInvoices($id=false)

     {

        

        if(!$id)

        {

            return;

        }

        $this->db->where('branch_id',$id);

        $query = $this->db->get('user_invoices');

        

        if($query->num_rows() > 0)

        {

            return $query->result();

        }

        else

        {

            return false;

        }

        

        

     }



    function get_user_order($user_id){

       $this->db->select('o.id as order_id,c.product_id,c.product_name,c.product_img,c.product_status1,op.order_id,o.payment_id,o.payment_follow_number,o.total_price,o.order_date,u.address,u.city_id,u.town,u.first_name,u.last_name,o.status_id,o.payment_id');

       $this->db->from('orders o');

       $this->db->join('orders_products op', 'op.order_id= o.id', 'left');

       $this->db->join('products c', 'c.product_id= op.product_id', 'left');

       $this->db->join('user_adress u', 'u.id= o.shipment_address', 'left');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       $this->db->where('o.user_id',$user_id);

       $this->db->where('payment_id!=','0');

       $this->db->group_by('op.order_id');

       $this->db->order_by("o.order_date","desc");

       $que = $this->db->get();

       return $que->result();

      

    } 

    function get_user_order_security_document($user_id){

       $this->db->select('o.id as order_idsi,c.product_id,c.product_name,c.product_img,c.product_status1,op.order_id,o.payment_id,o.payment_follow_number,o.total_price,o.order_date,o.user_ip,u.address,u.city_id,u.town,u.first_name,u.last_name,o.status_id,o.payment_id');

       $this->db->from('orders o');

       $this->db->join('orders_products op', 'op.order_id= o.id', 'left');

       $this->db->join('products c', 'c.product_id= op.product_id', 'left');

       $this->db->join('user_adress u', 'u.id= o.shipment_address', 'left');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       $this->db->where('o.user_id',$user_id);

       $this->db->group_by('op.order_id');

       $this->db->order_by("o.order_date","desc");

       $que = $this->db->get();

       return $que->result();

      

    }

    function get_order_detail_security_document($order_id){

       $this->db->select('o.id as order_id,o.payment_id,o.payment_follow_number,o.total_price,o.order_date,u.address,u.city_id,u.town,u.first_name,u.last_name,u.phone,o.status_id,o.payment_id,o.order_date');

       $this->db->from('orders o');

       $this->db->join('user_adress u', 'u.id= o.shipment_address', 'left');

       $this->db->where('o.id',$order_id);

       $que = $this->db->get();

       return $que->row();

      

    }

    function get_order_product_detail_security_document($order_id){

       $this->db->select('op.product_single_price,product_total_price,c.product_name,c.product_img,op.product_single_price,sum(op.product_total_price) as product_total_price');

       $this->db->from('orders_products op');

       $this->db->join('products c', 'c.product_id= op.product_id', 'left');

       $this->db->join('product_properties d', 'd.product_id= op.product_id', 'left');

       $this->db->where('op.order_id',$order_id);

       $this->db->group_by('op.product_id');

       $que = $this->db->get();

       return $que->result();

      

    }          

    function get_order_detail($order_id){

       $this->db->select('o.id as order_id,o.payment_id,o.payment_follow_number,o.total_price,o.order_date,u.address,u.city_id,u.town,u.first_name,u.last_name,o.status_id,o.payment_id,o.order_date');

       $this->db->from('orders o');

       $this->db->join('user_adress u', 'u.id= o.shipment_address', 'left');

       $this->db->where('o.id',$order_id);

       $this->db->where('payment_id!=','0');

       $que = $this->db->get();

       return $que->row();

      

    } 

    function get_order_product_detail($order_id){

       $this->db->select('op.product_single_price,pi.img_url,op.quantity,product_total_price,d.product_barcode,o.total_price,d.price_one,d.price_two,d.price_three,d.price_four,d.price_five,d.price_six,d.product_tax,c.product_name,c.product_img,op.product_single_price,sum(op.product_total_price) as product_total_price');

       $this->db->from('orders_products op');

       $this->db->join('products c', 'c.product_id= op.product_id', 'left');

       $this->db->join('product_properties d', 'd.product_id= op.product_id', 'left');
	   
	   $this->db->join('orders o','o.id=op.order_id','left');
	   
	   $this->db->join('product_images pi', 'pi.property_id= op.product_properties_id', 'left');

       $this->db->where('op.order_id',$order_id);

       $this->db->group_by('op.product_id');

       $que = $this->db->get();

       return $que->result();

      

    }
	
	function get_dealer_type($user_id)
	{
		$this->db->select("dealer_center.dealer_type");
		$this->db->from("users");
		$this->db->join("dealer","dealer.dealer_id=users.dealer_id","left");
		$this->db->join("dealer_center","dealer_center.dealer_center_id=dealer.dealer_center_id","left");
		$this->db->where("users.id",$user_id);
		
		$que = $this->db->get();
		
		return $que->row();
		
	}
	

    //Get User Delivery Adress

    function get_user_adress($user_id){

       $this->db->select('*');

       $this->db->from('user_adress');

       $this->db->where('user_id',$user_id);

       $que = $this->db->get();

       return $que->result();

      

    }



    function delete_adress($id){

        $this->db->where('id', $id);

        $que=$this->db->delete('user_adress'); 

        return $this->db->affected_rows();

    }



    //Get User Invoice Adress

    function get_user_invoice_adress($user_id){

       $this->db->select('*');

       $this->db->from('user_invoices');

       $this->db->where('branch_id',$user_id);

       $que = $this->db->get();

       return $que->result();

      

    }



    function delete_invoice_adress($id){

        $this->db->where('id', $id);

        $que=$this->db->delete('user_invoices'); 

        return $this->db->affected_rows();

    }

}





?>

