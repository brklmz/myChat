<?php

if (!defined('BASEPATH'))

    exit('No direct script access allowed');

class Product_model extends CI_Model{

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

 

    function get_product_search($search,$param,$startlimit,$finishlimit,$sort){

       

       $resarray = array();

       $likearray = array('c.product_name' =>$search ,'f.category_name' =>$search );

       $this->db->select('c.product_id,c.product_name,c.product_img,c.product_status1,d.product_market_price,d.product_paydoor_price,f.category_name,c.product_short_desc,f.category_id');

       $this->db->from('products c');

       $this->db->join('product_categories a', 'c.product_id= a.product_id', 'left');

       $this->db->join('categories f', 'f.category_id= a.category_id', 'left');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       if($search)

          $this->db->or_like($likearray);

       if($param) 

          $this->db->where('f.category_id',$param);

       $this->db->limit(30,$startlimit,$finishlimit);

       if($sort)

         $this->db->order_by("d.product_market_price",$sort);

       $que = $this->db->get();

       $res=$que->result(); 

       $this->db->select('c.product_id,c.product_name,c.product_img,c.product_status1,d.product_market_price,d.product_paydoor_price,f.category_name,c.product_short_desc,f.category_id');

       $this->db->from('products c');

       $this->db->join('product_categories a', 'c.product_id= a.product_id', 'left');

       $this->db->join('categories f', 'f.category_id= a.category_id', 'left');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       if($search)

          $this->db->or_like($likearray);

       if($param) 

          $this->db->where('f.category_id',$param);

       if($sort)

          $this->db->order_by("d.product_market_price",$sort);

       $que = $this->db->get();

       $resq=$que->result();

       foreach ($res as $k => $v) {

         $resarray[$k]=$v;

         $resarray[$k]->count=count($resq);

       }

        

       return $resarray; 

    }

    

    function find_categories($id,$startlimit=0,$finishlimit=0,$filter=false){

       $resarray = array();

       $this->db->select('c.product_id,c.product_name,c.product_img,c.product_status1,d.product_market_price,d.product_paydoor_price,g.category_id');

       $this->db->from('products c');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       $this->db->join('product_categories g', 'g.product_id= c.product_id', 'left');

       $this->db->where('g.category_id',$id);

       $this->db->limit(30,$startlimit,$finishlimit);

       if($filter)

         $this->db->order_by("d.product_market_price",$filter);

       $que = $this->db->get();

       $res=$que->result();

       $this->db->select('c.product_id');

       $this->db->from('products c');

       $this->db->join('product_categories g', 'g.product_id= c.product_id', 'left');

       $this->db->where('g.category_id',$id);

       $que = $this->db->get();

       $resq=$que->result();

       foreach ($res as $k => $v) {

         $resarray[$k]=$v;

         $resarray[$k]->count=count($resq);

       }

        

       return $resarray;  

    }





    function product_list(){

      $resarray = array();
       $this->db->select('*');
       $this->db->from('products');
       $this->db->where('product_status1',"1");
       $this->db->where('home_view',"1");
       $this->db->order_by("product_id","DESC");
       $que = $this->db->get();
       $res=$que->result();



   



        foreach ($res as $k => $v) {

         $price_change=$this->get_price_change_group($v->price_change_group_name); 

             if($price_change) {

                  $price_change=str_replace("`","", $price_change);
                  $price_change=$price_change;

                  $this->db->select('property_id,product_id,cap,price_change_group_name,parekende_fiyat');
                  $this->db->from('product_properties');
                  $this->db->where('product_id',$v->product_id);
                  $this->db->where('price_change_group_name',$price_change);
                  $this->db->group_by("price_change_group_name");
                  $que = $this->db->get();
                  $resq=$que->result();

                

                  $resarray[$k]=$v;
                  $resarray[$k]->size=$resq;
             } else {

                $resarray[$k]=$v;
                $resarray[$k]->size="";

             }      
       }

       return $resarray;

    }


  public function  get_price_change_group($id) {

       $this->db->select('name');
       $this->db->from('price_change_group');
       $this->db->where('id',$id);
       $this->db->limit(1);
       $que = $this->db->get();
       $res=$que->row();

       if($res)
          return $res->name;
        else
          return false;

  }
   function product_list_search($category_id){

        $resarray = array();

         $this->db->select('p.*,pc.category_id');

         $this->db->from('products p');

         $this->db->join('product_categories pc', 'p.product_id= pc.product_id', 'left');

         $this->db->where('category_id',$category_id);

         $this->db->group_by('pc.product_id');

         $que = $this->db->get();

         $res=$que->result();

         foreach ($res as $k => $v) {

          $this->db->select('property_id,product_id,cap,price_change_group_name,parekende_fiyat');

          $this->db->from('product_properties');

          $this->db->where('product_id',$v->product_id);

          $this->db->group_by('product_id');

          $this->db->group_by('cap');

          $que = $this->db->get();

          $resq=$que->result();

         $resarray[$k]=$v;

         $resarray[$k]->size=$resq;

       }

       return $resarray;

      }



    function product_list_details($filter){



      $likearray = array('p.product_name' =>$filter["search"]);

      $resarray = array();

       $this->db->select('p.product_name,p.product_img,p.product_id,pp.property_id');
       $this->db->from('products p');
       $this->db->join('product_properties pp', 'p.product_id= pp.product_id', 'left');

       if($filter["category"]){
          $this->db->join('product_categories pc', 'pc.product_id= pp.product_id', 'left');
          $this->db->where('pc.category_id',$filter["category"]);
       }

       if($filter["varyantlist1"])
         $this->db->where_in('pp.product_variant_id1',$filter["varyantlist1"]);

       if($filter["varyantlist2"])
         $this->db->where_in('pp.product_variant_id2',$filter["varyantlist2"]);

       if($filter["varyantlist3"])
         $this->db->where_in('pp.product_variant_id3',$filter["varyantlist3"]);

       if($filter["varyantlist4"])
         $this->db->where_in('pp.product_variant_id4',$filter["varyantlist4"]);

       if($filter["varyantlist5"])
         $this->db->where_in('pp.product_variant_id5',$filter["varyantlist5"]);

       if($filter["varyantlist6"])
         $this->db->where_in('pp.product_variant_id6',$filter["varyantlist6"]);

       if($filter["search"])
           $this->db->or_like($likearray);


         $this->db->group_by('pp.property_id');
         $que = $this->db->get();
         $res=$que->result();


        foreach ($res as $k => $v) {

          $resarray[$v->product_id]=$v;

       }


       return $resarray;

    }





    function get_product($id){

       $this->db->select('c.product_id,c.product_name,c.product_img,c.product_status1,c.product_desc,c.product_short_desc,stock_price,stok_status');

       $this->db->from('products c');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       $this->db->where('c.product_id',$id);

       $que = $this->db->get();

       return $que->result();

    } 

    function get_product_single($id){

       $this->db->select('*');

       $this->db->from('products c');

       $this->db->where('c.product_id',$id);

       $this->db->limit(1);

       $que = $this->db->get();

       return $que->result();

    } 


    function get_product_property_detail($id){

       $this->db->select('c.product_id,c.product_name,c.product_img,c.product_status1,c.product_desc,c.product_short_desc,d.*');

       $this->db->from('products c');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       $this->db->where('c.product_id',$id);

       $que = $this->db->get();

       return $que->row();
    } 


    function get_product_prop($id){

       $this->db->select('c.product_id,c.product_name,c.product_img,c.product_status1,c.product_desc,c.product_short_desc,stock_price,stok_status');

       $this->db->from('products c');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       $this->db->where('d.property_id',$id);

       $que = $this->db->get();

       return $que->result();

      

    } 



    function get_product_price($id){

       $this->db->select('MAX(parekende_fiyat) as max_fiyat,MIN(parekende_fiyat) as min_fiyat');

       $this->db->from('product_properties c');

       $this->db->where('c.product_id',$id);

       $que = $this->db->get();

       return $que->result();

      

    } 

    function get_product_category($id){

       $this->db->select('a.category_name,a.category_id,a.parent_id,count(b.product_id) as toplam_adet');

       $this->db->from('categories a');

       $this->db->join('product_categories b', 'a.parent_id= b.category_id', 'left');

       $this->db->where('a.parent_id',$id);

       $this->db->group_by('a.category_id');

       $que = $this->db->get();

       return $que->result();

      

    }

    /*

    function insert_basket($tables,$where){

       return $where->category;

      

    } */    

    function basket_delete($property_id){

      $user_session=$this->session->userdata('logged_in_front');
      $user_id=$user_session['user_id'];


      $where=array('property_id'=>$property_id,'user_id'=>$user_id);
      $this->db->where($where);
      $this->db->delete('basket');
      return $this->db->affected_rows(); 

    }


    function insert_basket($product_id,$quantity,$property_id,$aks_value,$customer_name=false,$is_same=false){

      $response = array();
      $last_id=0;

      $ip_adresi = $_SERVER['REMOTE_ADDR'];

      if($this->session->userdata('logged_in_front')!=null)
        $user_session=$this->session->userdata('logged_in_front');

      else
        $user_session=0;

      if($user_session!=null)
        $user_session_id=$user_session['user_id'];
      else
        $user_session_id=0;

       $session_id = $this->session->session_id;
       $succ=0;
       $s_stat=0;

       $this->db->select('stok_status,stock_price,maxsimum_single_purchase');
       $this->db->from('product_properties');
       $this->db->where("property_id",$property_id);
       $que = $this->db->get();
       $stok_durumu=$que->row();

      if(!$aks_value) $aks_value=0;

      $response[0] = new stdClass();
      if($stok_durumu->stok_status>0) {

              if($stok_durumu->stock_price>0 && $quantity<=$stok_durumu->stock_price && $quantity<=$stok_durumu->maxsimum_single_purchase) {

               $this->db->select('basket_id');
               $this->db->from('basket');
               $this->db->where("user_id",$this->session->userdata('logged_in_front')['user_id']);
               $this->db->where("property_id",$property_id);
               $que = $this->db->get();
               $sepet_durumu=$que->row();
               if($sepet_durumu)
                 $s_stat=$sepet_durumu->basket_id;

               if($s_stat) {

                   $quas='quantity+'.$quantity;
                   $this->db->where("user_id",$this->session->userdata('logged_in_front')['user_id']);
                   $this->db->where("property_id",$property_id);
                   $this->db->set('quantity', $quas, FALSE);
                   $ques=$this->db->update('basket');

                   if($ques)
                    $succ=1;

               } else {

                  if(!$customer_name)
                    $customer_name=0;

                  if(!$is_same)
                    $is_same=0;

                   $data=array('user_id'=>$user_session_id,'status'=>'1','session'=>$session_id,' ip_address'=>$ip_adresi,'product_id'=>$product_id,'property_id'=>$property_id,'quantity'=>$quantity,'aks_value'=>$aks_value,'customer_name'=>$customer_name,'is_same'=>$is_same,'date'=>date('y-m-d H:i:s'));

                    $this->db->insert('basket', $data);
                     $last_id=$this->db->insert_id();

                     if($last_id)
                        $succ=1;
                     
               }

                 if(!$succ) {

                    $response[0]->status=false;
                    $response[0]->message="Ürün ekleme işlemi sırasında hata oluştu";
                  } else {

                     
                      $response[0]->status=true;
                      $response[0]->last_id=$last_id;
                      $response[0]->message="Başarılı";
                  }  
              } 
              else {

                
                  $response[0]->status=false;
                  $response[0]->message="Stokta yeterli ürün yoktur.";
              }


      } else {

        
          $response[0]->status=false;
          $response[0]->message="Stokta ürün bulunmuyor.";

          
      }

 
       return $response;
      

    }

    function update_basket($basket_id,$quant){

  
    
     $this->db->select('p.stok_status,p.stock_price');
     $this->db->from('basket b');
     $this->db->join('product_properties p', 'b.property_id= p.property_id', 'left');
     $this->db->where("b.basket_id",$basket_id);
     $this->db->limit(1);
     $que = $this->db->get();
     $stok_durumu=$que->row();
     
      $response[0] = new stdClass();

      if($stok_durumu->stok_status>0) {


              if($stok_durumu->stock_price>0 && $quant<=$stok_durumu->stock_price) {
          
                 $where=array('basket_id'=>$basket_id);
                 $data=array('quantity'=>$quant);
                 $this->db->where($where);
                 $ques=$this->db->update('basket', $data);


                 if(!$ques) {

                    $response[0]->status=false;
                    $response[0]->message="Ürün ekleme işlemi sırasında hata oluştu";
                  } else {

                     
                      $response[0]->status=true;
                      $response[0]->message="Başarılı";
                  }  
              } 
              else {

                
                  $response[0]->status=false;
                  $response[0]->message="Stokta yeterli ürün yoktur En fazla ".$stok_durumu->stock_price." adet ürün alabilirsiniz.";
              }


      } else {

        
          $response[0]->status=false;
          $response[0]->message="Stokta ürün bulunmuyor.";

          
      }
    
     

        return $response;

      

    }

    function delete_basket($basket_id){
      $where=array('basket_id'=>$basket_id);
      $this->db->where($where);
      $this->db->delete('basket');
      return $this->db->affected_rows(); 

    }

   function check_product($product_id){
      
       $this->db->select('product_id');

       $this->db->from('products');

       $query=$this->db->where('product_id',$product_id);

       $que=$query->get()->result();

       return count($que); 

    }

   function check_order($order_id){

      
       $this->db->select('id');

       $this->db->from('orders_products');

       $query=$this->db->where('order_id',$order_id);

       $que=$query->get()->result();

       return count($que); 

    }

   function read_basket(){ 

      if($this->session->userdata('logged_in_front')!=null)

        $user_session=$this->session->userdata('logged_in_front');

      else

        $user_session=0;

      if($user_session!=null)

        $user_session_id=$user_session['user_id'];

      else

        $user_session_id=0;

      $session_id = $this->session->session_id;

       if($user_session_id>0) {

          $where=array('user_id' => $user_session_id,'session' => $session_id); 

          $group=array('user_id','session');

       }

        else{

          $where=array('session' => $session_id); 

          $group='session';

        }

       $this->db->select('d.quantity as toplam_adet,p.product_name,d.basket_id,p.product_img,pp.parekende_fiyat,pp.property_id');

       $this->db->from('basket d');

       $this->db->join('products p','d.product_id = p.product_id');

       $this->db->join('product_properties pp','d.product_id = pp.product_id');

       $this->db->or_where($where);

       $this->db->group_by("pp.product_id");

       $this->db->group_by("d.product_id");

       $this->db->group_by("d.basket_id");

       $que = $this->db->get();

       return $que->result();

     }

   function read_basket_result(){ 

      if($this->session->userdata('logged_in_front')!=null)

        $user_session=$this->session->userdata('logged_in_front');

      else

        $user_session=0;

      if($user_session!=null)

        $user_session_id=$user_session['user_id'];

      else

        $user_session_id=0;

      $session_id = $this->session->session_id;

       if($user_session_id>0) {

          $where=array('user_id' => $user_session_id,'session' => $session_id); 

          $group=array('user_id','session');

       }

        else{

          $where=array('session' => $session_id); 

          $group='session';

        }

       $this->db->select('sum(d.quantity) as toplam_adet,sum(d.quantity*b.parekende_fiyat) as toplam_fiyat');

       $this->db->from('basket d');

       $this->db->join('product_properties b', 'b.product_id= d.product_id', 'left');

       $this->db->or_where($where);

       $this->db->group_by("d.product_id");

       $que = $this->db->get();

       return $que->result();

     }

    function read_basket_detail(){ 

        

      $resarray=array();

      if($this->session->userdata('logged_in_front')!=null)
        $user_session=$this->session->userdata('logged_in_front');
      else
        $user_session=0;
      if($user_session!=null) {
        $user_session_id=$user_session['user_id'];
      }

      else
        $user_session_id=0;

      $session_id = $this->session->session_id;
      $group=array('d.product_id','f.product_id','c.product_id');
       if($user_session_id>0) {
          $where=array('user_id' => $user_session_id,'session' => $session_id); 
       }
        else{
          $where=array('session' => $session_id); 
        }


       $this->db->select('c.product_id,c.product_name,c.product_img,d.quantity as quantity,f.parekende_fiyat,d.basket_id,f.property_id,f.product_tax,f.price_one,f.price_two,f.price_three,f.price_four,f.price_five,f.price_six,d.is_same');
       $this->db->from('basket d');
       $this->db->join('products c', 'd.product_id= c.product_id', 'left');
       $this->db->join('product_properties f', 'f.property_id= d.property_id', 'left');
       $this->db->or_where($where);
       $this->db->group_by("d.product_id");
       $this->db->group_by("d.basket_id");
       $this->db->group_by("f.product_id");
        $this->db->order_by("d.is_same","ASC");
       $que = $this->db->get();
       $res=$que->result();

       foreach ($res as $k => $v) {

        $this->db->select('v.name as varone,vt.name as vartwo,vth.name varthree,vft.name as varfour,vfv.name as varfive,fs.name as varsix');
        $this->db->from('product_properties pp');
        $this->db->join('variant v', 'v.id= pp.product_variant_id1', 'left');
        $this->db->join('variant vt', 'vt.id= pp.product_variant_id2', 'left');
        $this->db->join('variant vth', 'vth.id= pp.product_variant_id3', 'left');
        $this->db->join('variant vft', 'vft.id= pp.product_variant_id4', 'left');
        $this->db->join('variant vfv', 'vfv.id= pp.product_variant_id5', 'left');
        $this->db->join('variant fs', 'fs.id= pp.product_variant_id6', 'left');
        $this->db->where('property_id',$v->property_id);
        $this->db->group_by('property_id');
        $que = $this->db->get();
        $ques=$que->result();

         $resarray[$k]=$v;
         $resarray[$k]->varyantlar=$ques;

       }


        return $resarray;

     }

    function get_product_brand($id){

       $this->db->select('*');

       $this->db->from('brands c');

       $que = $this->db->get();

       return $que->result();

      

    } 

    function get_product_image($id){

       $this->db->select('c.product_id,c.property_id,c.img_url');

       $this->db->from('product_images c');

       $this->db->where('c.product_id',$id);

       $que = $this->db->get();

       return $que->result();

      

    }

    function get_category_product($id){

       $this->db->select('a.product_id,a.product_name,a.product_img,d.product_categories_id,f.product_market_price,f.product_paydoor_price');

       $this->db->from('products a');

       $this->db->join('product_categories d', 'd.product_id= a.product_id', 'left');

       $this->db->join('product_properties f', 'f.product_id= a.product_id', 'left');

       $this->db->where('d.category_id',$id);

       $this->db->limit(5);

       $que = $this->db->get();

       return $que->result();

    }

    function product_categories_list($product_id){

        $this->db->select('*');

        $this->db->from('product_categories a');

        $this->db->join('categories e', 'a.category_id= e.category_id', 'left');

        $this->db->where('a.product_id',$product_id);

        $que = $this->db->get();

        return $que->result();

    }

    function product_brands_list($product_id){

        $this->db->select('*');

        $this->db->from('brand_products a');

        $this->db->join('brands e', 'a.brand_id= e.brand_id', 'left');

        $this->db->where('a.product_id',$product_id);

        $que = $this->db->get();

        return $que->result();

    }    

    

    function get_lastvisit_product($arr){



       $this->db->select('c.product_id,c.product_name,c.product_img,c.product_status1,d.product_market_price,d.product_paydoor_price,f.category_id');

       $this->db->from('products c');

       $this->db->join('product_categories a', 'c.product_id= a.product_id', 'left');

       $this->db->join('categories f', 'f.category_id= a.category_id', 'left');

       $this->db->join('product_properties d', 'd.product_id= c.product_id', 'left');

       $this->db->where_in('c.product_id',$arr);

       $this->db->limit(8);

       $que = $this->db->get();

       return $que->result();

    }

     //1. Varyant Grubu ile ilgili Modeller

      function get_single_properties($prop_id) {



       $this->db->select('pp.property_id,p.product_name,pp.product_id,pp.product_goods_code,parekende_fiyat,pp.product_barcode,pp.product_barcode_none,pp.product_tax,v.name as varone,vt.name as vartwo,vth.name varthree,vft.name as varfour,vfv.name as varfive,fs.name as varsix');

       $this->db->from('product_properties pp');

       $this->db->join('products p', 'pp.product_id= p.product_id', 'left');

       $this->db->join('variant v', 'v.id= pp.product_variant_id1', 'left');

       $this->db->join('variant vt', 'vt.id= pp.product_variant_id2', 'left');

       $this->db->join('variant vth', 'vth.id= pp.product_variant_id3', 'left');

       $this->db->join('variant vft', 'vft.id= pp.product_variant_id4', 'left');

       $this->db->join('variant vfv', 'vfv.id= pp.product_variant_id5', 'left');

       $this->db->join('variant fs', 'fs.id= pp.product_variant_id6', 'left');

       $this->db->where('property_id',$prop_id);

       $this->db->group_by('pp.property_id');;

       $que = $this->db->get();

       return $que->result();





    }



         //1. Varyant Grubu ile ilgili Modeller

     /* function get_single_properties($prop_id) {



       $this->db->select('pp.property_id,p.product_name,pp.product_id,pp.product_goods_code,parekende_fiyat,pp.iskontosuz_toptan_fiyat,pp.product_barcode,pp.product_barcode_none,pp.product_tax,v.name as varone,vt.name as vartwo,vth.name varthree,vft.name as varfour,vfv.name as varfive,fs.name as varsix');

       $this->db->from('product_properties pp');

       $this->db->join('products p', 'pp.product_id= p.product_id', 'left');

       $this->db->join('variant v', 'v.id= pp.product_variant_id1', 'left');

       $this->db->join('variant vt', 'vt.id= pp.product_variant_id2', 'left');

       $this->db->join('variant vth', 'vth.id= pp.product_variant_id3', 'left');

       $this->db->join('variant vft', 'vft.id= pp.product_variant_id4', 'left');

       $this->db->join('variant vfv', 'vfv.id= pp.product_variant_id5', 'left');

       $this->db->join('variant fs', 'fs.id= pp.product_variant_id6', 'left');

       $this->db->where('property_id',$prop_id);

       $this->db->group_by('pp.property_id');;

       $que = $this->db->get();

       return $que->result();





    }*/



    //1. Varyant Grubu ile ilgili Modeller

     function get_prop_properties($pr_id) {

       $this->db->select('p.property_id,p.product_id,product_variant_group_id1,product_variant_id1,p.product_goods_code,parekende_fiyat,product_barcode,product_barcode_none,product_tax,stock_price,stok_status');

       $this->db->from('product_properties p');

       $this->db->where('p.product_id',$pr_id);

       $que = $this->db->get();

       return $que->result();

    }



      function get_varyant_group_properties($pr_id) {

       $this->db->select('p.property_id,p.product_id,product_variant_group_id1,product_variant_id1,gr.variant_title_name,v.name,p.product_goods_code,parekende_fiyat,product_barcode,product_barcode_none,product_tax,stock_price,stok_status,p.price_one');

       $this->db->from('product_properties p');

       $this->db->join('variant_groups gr', 'gr.variant_group_id= p.product_variant_group_id1', 'left');

       $this->db->join('variant v', 'v.id= p.product_variant_id1', 'left');

       $this->db->where('p.product_id',$pr_id);

       $this->db->where('v.name !=',null);

       $this->db->group_by("p.product_variant_group_id1");

       $this->db->group_by("p.product_variant_id1");

       $this->db->order_by("v.name","ASC");

       $que = $this->db->get();

       return $que->result();

    }

    //1. Varyant Grubu ile ilgili Modeller

    function get_product_varyant_group_ones($data) {

  
      $this->db->select('p.property_id,p.product_id,product_variant_group_id2,product_variant_id2,gr.variant_title_name,v.name,p.product_goods_code,parekende_fiyat,product_barcode,product_barcode_none,product_tax,stock_price,stok_status,p.price_one,p.price_two,p.price_three,p.price_four,p.price_five,p.price_six');

       $this->db->from('product_properties p');

       $this->db->join('variant_groups gr', 'gr.variant_group_id= p.product_variant_group_id2', 'left');

       $this->db->join('variant v', 'v.id= p.product_variant_id2', 'left');

       $this->db->where('p.product_id',$data["product_id"]);

       $this->db->where('p.product_variant_id1',$data["product_var_one"]);

       $this->db->where('v.name !=',null);

       $this->db->group_by("p.product_variant_group_id2");

       $this->db->group_by("p.product_variant_id2");

       $this->db->order_by("v.name","ASC");

       $que = $this->db->get();

       return $que->result();

    }

    //2. Varyant Grubu ile ilgili Modeller

    function get_product_varyant_group_two($data) {

  
      $this->db->select('p.property_id,p.product_id,product_variant_group_id3,product_variant_id3,gr.variant_title_name,v.name,p.product_goods_code,parekende_fiyat,product_barcode,product_barcode_none,product_tax,stock_price,stok_status,p.price_one,p.price_two,p.price_three,p.price_four,p.price_five,p.price_six');

       $this->db->from('product_properties p');

       $this->db->join('variant_groups gr', 'gr.variant_group_id= p.product_variant_group_id3', 'left');

       $this->db->join('variant v', 'v.id= p.product_variant_id3', 'left');

       $this->db->where('p.product_id',$data["product_id"]);

       $this->db->where('p.product_variant_id1',$data["product_var_one"]);

       $this->db->where('p.product_variant_id2',$data["product_var_two"]);

       $this->db->where('v.name !=',null);

       $this->db->group_by("p.product_variant_group_id3");

       $this->db->group_by("p.product_variant_id3");

       $this->db->order_by("v.name","ASC");

       $que = $this->db->get();

       return $que->result();

    }

    //3. Varyant Grubu ile ilgili Modeller
    /*
            product_vary:"",
        product_varls:"",
        product_varth:"",
        product_varft:"",
        */
    function get_product_varyant_group_three($data) {

      $this->db->select('p.property_id,p.product_id,product_variant_group_id4,product_variant_id4,gr.variant_title_name,v.name,p.product_goods_code,parekende_fiyat,product_barcode,product_barcode_none,product_tax,stock_price,stok_status,p.price_one,p.price_two,p.price_three,p.price_four,p.price_five,p.price_six');

       $this->db->from('product_properties p');

       $this->db->join('variant_groups gr', 'gr.variant_group_id= p.product_variant_group_id4', 'left');

       $this->db->join('variant v', 'v.id= p.product_variant_id4', 'left');

       $this->db->where('p.product_id',$data["product_id"]);

       $this->db->where('p.product_variant_id1',$data["product_var_one"]);

       $this->db->where('p.product_variant_id2',$data["product_var_two"]);

       $this->db->where('p.product_variant_id3',$data["product_var_three"]);

       $this->db->where('v.name !=',null);

       $this->db->group_by("p.product_variant_group_id4");

       $this->db->group_by("p.product_variant_id4");

       $this->db->order_by("v.name","ASC");

       $que = $this->db->get();

       return $que->result();

    }

    //4. Varyant Grubu ile ilgili Modeller

    function get_product_varyant_group_four($data) {

      $this->db->select('p.property_id,p.product_id,product_variant_group_id5,product_variant_id5,gr.variant_title_name,v.name,p.product_goods_code,parekende_fiyat,product_barcode,product_barcode_none,product_tax,stock_price,stok_status,p.price_one,p.price_two,p.price_three,p.price_four,p.price_five,p.price_six');

       $this->db->from('product_properties p');

       $this->db->join('variant_groups gr', 'gr.variant_group_id= p.product_variant_group_id5', 'left');

       $this->db->join('variant v', 'v.id= p.product_variant_id5', 'left');

       $this->db->where('p.product_id',$data["product_id"]);

       $this->db->where('p.product_variant_id1',$data["product_var_one"]);

       $this->db->where('p.product_variant_id2',$data["product_var_two"]);

       $this->db->where('p.product_variant_id3',$data["product_var_three"]);

       $this->db->where('p.product_variant_id4',$data["product_var_four"]);

       $this->db->where('v.name !=',null);

       $this->db->group_by("p.product_variant_group_id5");

       $this->db->group_by("p.product_variant_id5");

       $this->db->order_by("v.name","ASC");

       $que = $this->db->get();

       return $que->result();

    }

    //5. Varyant Grubu ile ilgili Modeller

    function get_product_varyant_group_five($data) {

      $this->db->select('p.property_id,p.product_id,product_variant_group_id6,product_variant_id6,gr.variant_title_name,v.name,stock_price,stok_status,p.price_one,p.price_two,p.price_three,p.price_four,p.price_five,p.price_six');

       $this->db->from('product_properties p');

       $this->db->join('variant_groups gr', 'gr.variant_group_id= p.product_variant_group_id6', 'left');

       $this->db->join('variant v', 'v.id= p.product_variant_id6', 'left');

       $this->db->where('p.product_id',$data["product_id"]);

       $this->db->where('p.product_variant_id1',$data["product_var_one"]);

       $this->db->where('p.product_variant_id2',$data["product_var_two"]);

       $this->db->where('p.product_variant_id3',$data["product_var_three"]);

       $this->db->where('p.product_variant_id4',$data["product_var_four"]);

       $this->db->where('p.product_variant_id5',$data["product_var_five"]);

       $this->db->where('v.name !=',null);

       $this->db->group_by("p.product_variant_group_id6");

       $this->db->group_by("p.product_variant_id6");

       $this->db->order_by("v.name","ASC");

       $que = $this->db->get();

       return $que->result();

    }

     public function get_varyant_list($var_id) {

       $this->db->select('*');

       $this->db->from('variant');

       $this->db->where('varyant_group_id',$var_id);

       $que = $this->db->get();

       return $que->result();

    }

    //---

    function get_category_info($id){



      $resarray=array();

       $this->db->select('c.*,gr.variant_title_name as var1,grtwo.variant_title_name as var2,grthree.variant_title_name as var3,grfour.variant_title_name as var4,grfive.variant_title_name as var5,grsix.variant_title_name as var6');

       $this->db->from('categories c');

       $this->db->join('variant_groups gr', 'gr.variant_group_id= c.variant_group_id1', 'left');

       $this->db->join('variant_groups grtwo', 'grtwo.variant_group_id= c.variant_group_id2', 'left');

       $this->db->join('variant_groups grthree', 'grthree.variant_group_id= c.variant_group_id3', 'left');

       $this->db->join('variant_groups grfour', 'grfour.variant_group_id= c.variant_group_id4', 'left');

       $this->db->join('variant_groups grfive', 'grfive.variant_group_id= c.variant_group_id5', 'left');

       $this->db->join('variant_groups grsix', 'grsix.variant_group_id= c.variant_group_id6', 'left');

       $this->db->where_in('category_id',$id);

       $que = $this->db->get();

       $res=$que->row();



         $resarray[0]=$res;

         if($res->variant_group_id1)

              $resarray[0]->varyant_list1=$this->get_varyant_list($res->variant_group_id1);

         if($res->variant_group_id2)

              $resarray[0]->varyant_list2=$this->get_varyant_list($res->variant_group_id2);

         if($res->variant_group_id3)

              $resarray[0]->varyant_list3=$this->get_varyant_list($res->variant_group_id3);

          if($res->variant_group_id4)

              $resarray[0]->varyant_list4=$this->get_varyant_list($res->variant_group_id4); 

          if($res->variant_group_id5)

              $resarray[0]->varyant_list5=$this->get_varyant_list($res->variant_group_id5); 

          if($res->variant_group_id6)

              $resarray[0]->varyant_list6=$this->get_varyant_list($res->variant_group_id6);                

       

        

       return $resarray; 





    }


    //Toplam fiyat aralığına göre kargo fiyatı dönmesi
   function cargo_price($total){

       $this->db->select('value');
       $this->db->from('cargo_payment');
       $query=$this->db->where('base_value<=',$total);
       $query=$this->db->where('top_value>=',$total);
       $query=$this->db->where('status',"1");
       $que=$query->get()->result();

       if(count($que)>0)
          return $que;
       else
          return false;    

    }

    //$this->session->userdata('logged_in_front')['user_id']
     function dealer_prices(){

        $user_id=$this->session->userdata('logged_in_front')['user_id'];

       $this->db->select('d.branch_name,dc.dealer_type');
       $this->db->from('dealer d');
       $this->db->join('dealer_center dc', 'dc.dealer_center_id= d.dealer_center_id', 'left');
       $query=$this->db->where('d.user_id', $user_id);
       $que=$query->get()->row();

       $dealers=(int)$que->dealer_type;
       return $dealers;   

    }


   function get_dealer_discount($user_id){
      $this->db->select('dt.impact_value,dt.value');
       $this->db->from('users u');
       $this->db->join('dealer d', 'u.dealer_id= d.dealer_id', 'left');
       $this->db->join('dealer_center dc', 'dc.dealer_center_id= d.dealer_center_id', 'left');
       $this->db->join('dealer_type dt', 'dt.id= dc.dealer_type', 'left');
       $this->db->where('u.id',$user_id);
       $que = $this->db->get();
       return  $que->row();
    }


}