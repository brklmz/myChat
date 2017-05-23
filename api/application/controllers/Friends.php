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

class Friends extends REST_Controller {

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

    

	/*Sohbet  için arkadaş ekle */
	public function add_friend_get($user_id,$other_id){
       
		$json = file_get_contents("http://95.85.26.49:8000/friend/addFriend/".$user_id."/".$other_id); // this will require php.ini to be setup to allow fopen over URLs
		$j = json_decode($json);

        // Usually a model is to be used for this.
		file_get_contents("http://95.85.26.49:8000/friend/addFriend/".$other_id."/".$user_id);

        if ($j){

            $message = [
                'status'=>TRUE,
                'res' => $j
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
	
	/*Arkadaşları getir*/
	public function friends_get(){
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
}
