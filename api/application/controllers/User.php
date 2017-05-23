<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        /* Dil Ayarı bununla oluyor normalde ama problem var incele
        if (is_array($this->response->lang)){
          $this->load->language('application', $this->response->lang[2]);
        }
        else {
          $this->load->language('application', $this->response->lang);
        }
        */
        date_default_timezone_set('Europe/Istanbul');
        $this->load->model('general/Dbi_model');   
        $this->load->model('user/User_model'); 

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function check_user_get(){
        // Users from a data store e.g. database
            
        //http://127.0.0.1/kalvers/api/user/check_user?email=gul.nurullah@gmail.com&password=123456
        $username = $this->get('username');
        $password = $this->get('password');

        // If the id parameter doesn't exist return all the users

        if (empty($username) || empty($password)){
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were founds'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  //HTTP_NOT_FOUND
        }


        $user=$this->User_model->userAuthentication($username,$password);

        // Usually a model is to be used for this.


        if ($user){

            $message = [
                'status'=>TRUE,
                'user' => $user
            ];

            $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function users_add_get() {
        
        //http://127.0.0.1/kalvers/api/user/users_add?name=Murat&surname=tar%C4%B1k&telefon=55555555555&password=123456&email=admisn@gmail.com

         $data = array(
              "ad"=> $this->get('name'),
              "soyad"=> $this->get('surname'),
              "username"=> $this->get('username'),
              "sifre"=> md5($this->get('password')),
              "tarih"=>date("Y-m-d H:i:s"),
              "img_url"=> "img/profile.png",
              "status"=> "0"
        );

        $result=$this->User_model->checkEmail_users($this->get('username'));

        if($result==0) {

                 $result=$this->Dbi_model->insert("uyeler",$data);
                $insert_id=$this->db->insert_id();// En son eklenenin idsini veriyor.

                if($insert_id) {

                    $message = [
                        'status'=>TRUE,
                        'id' => $insert_id, // Automatically generated by the model
                        'message' => 'Added a user'
                    ];

                    $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                } 
                else {
                   
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Kullanıcı Bulunamadı'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

                }

        } else {

            $this->set_response([
                'status' => FALSE,
                'message' => 'Bu kullanıcı adına ait farkli bir kayit bulunmaktadir.Tekrar Deneyiniz.'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }
         
       

        
    }

    public function users_delete(){
        
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

	/*Sohbet edilebilecek kişileri getirir (şu an kullanılmıyor)*/
	public function users_all_get(){
        // http://127.0.0.1/kalvers/api/task/select_all
           $id = $this->get('user_id');
        //query($table,$where='',$limit='',$order='',$select='',$ret_type='ob')
		$json = file_get_contents("http://95.85.26.49:8000/friend/getFriends/".$id); // this will require php.ini to be setup to allow fopen over URLs
		$j = json_decode($json);
		$ids = [];
		foreach($j as $v)
			$ids += $v->list;
		
        $users=$this->User_model->getFriends($ids);

        // Usually a model is to be used for this.

        if ($users){

            $message = [
                'status'=>TRUE,
                'users' => $users
            ];

            $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

	public function user_info_get(){
        // Users from a data store e.g. database
            
        //http://127.0.0.1/kalvers/api/user/check_user?email=gul.nurullah@gmail.com&password=123456
        $user_id = $this->get('user_id');
       

        // If the id parameter doesn't exist return all the users

        /*if (empty($email) || empty($password)){
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were founds'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  //HTTP_NOT_FOUND
        }*/


        $user=$this->User_model->getUser($user_id);

        // Usually a model is to be used for this.


        if ($user){

            $message = [
                'status'=>TRUE,
                'user' => $user
            ];

            $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

	public function user_get(){
        // Users from a data store e.g. database
            
        //http://127.0.0.1/kalvers/api/user/check_user?email=gul.nurullah@gmail.com&password=123456
        $username = $this->get('username');
       

        // If the id parameter doesn't exist return all the users

        /*if (empty($email) || empty($password)){
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were founds'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  //HTTP_NOT_FOUND
        }*/


        $user=$this->Dbi_model->query('uyeler',['username'=>$username]);

        // Usually a model is to be used for this.


        if ($user){

            $message = [
                'status'=>TRUE,
                'user' => $user[0]
            ];

            $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
	
    public function name_update_post() {
        $uye_id = $this->post('user_id');
        //http://127.0.0.1/kalvers/api/user/users_add?name=Murat&surname=tar%C4%B1k&telefon=55555555555&password=123456&email=admisn@gmail.com
	
         $data = array(
              "ad"=> $this->post('first_name'),
        );

        $result=$this->Dbi_model->update("uyeler",$data,['uye_id'=>$uye_id]);
        

        if($result) {

            $message = [
                'status'=>TRUE, // Automatically generated by the model
                'message' => 'update a user'
            ];

            $this->set_response($message, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        } 
        else {
            $this->response(NULL, REST_Controller::HTTP_OK); // BAD_REQUEST (400) being the HTTP response code
        }

        
    }
	
	public function last_name_update_get() {
        $uye_id = $this->get('user_id');
        //http://127.0.0.1/kalvers/api/user/users_add?name=Murat&surname=tar%C4%B1k&telefon=55555555555&password=123456&email=admisn@gmail.com
	
         $data = array(
              "soyad"=> $this->get('last_name'),
        );

        $result=$this->Dbi_model->update("uyeler",$data,['uye_id'=>$uye_id]);
        

        if($result) {

            $message = [
                'status'=>TRUE, // Automatically generated by the model
                'message' => 'update a user'
            ];

            $this->set_response($message, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        } 
        else {
            $this->response(NULL, REST_Controller::HTTP_OK); // BAD_REQUEST (400) being the HTTP response code
        }

        
    }

	public function pass_update_get() {
        $uye_id = $this->get('user_id');
        $pass = $this->get('pass');
        $new_pass = $this->get('new_pass');
        //http://127.0.0.1/kalvers/api/user/users_add?name=Murat&surname=tar%C4%B1k&telefon=55555555555&password=123456&email=admisn@gmail.com
	
        $data = array(
              "uye_id"=> $uye_id,
              "sifre"=> md5($pass)
        );

        
        
		if(empty($pass) || empty($new_pass)){
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were founds'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  //HTTP_NOT_FOUND
        }
		
		$result=$this->Dbi_model->query("uyeler",$data);

		$data = array(
              "uye_id"=> $uye_id,
              "sifre"=> md5($new_pass)
        );
		
        if($result) {

			$result=$this->Dbi_model->update("uyeler",$data,['uye_id'=>$uye_id]);
			if($result){
				$message = [
					'status'=>TRUE, // Automatically generated by the model
					'message' => 'update a user'
				];
				
				$this->set_response($message, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
			}
			else
				$this->response(NULL, REST_Controller::HTTP_OK);

            
        } 
        else {
            $this->response(NULL, REST_Controller::HTTP_OK); // BAD_REQUEST (400) being the HTTP response code
        }

        
    }
	
	public function upload_img_post() {
        $uye_id= $this->post('user_id');
        $img_url= $this->post('img_url');
        //http://127.0.0.1/kalvers/api/user/users_add?name=Murat&surname=tar%C4%B1k&telefon=55555555555&password=123456&email=admisn@gmail.com

		$duzelt = str_replace(' ', '+', $img_url);
        

        //$result=$this->Dbi_model->update('tb_uye',$data,['uye_id'=>$uye_id]);
		$result=$this->Dbi_model->update("uyeler",['img_url'=>$duzelt],['uye_id'=>$uye_id]);

        if($result) {
			
			$message = [
				'status'=>TRUE,
				'message' => 'Fotoğraf yüklendi'
			];

			$this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code

        } else {

            $this->set_response([
                'status' => FALSE,
                'message' => 'Aynı fotoğraf yüklenmiş olabilir.'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }
         
       

        
    }
}