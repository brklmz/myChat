<?php

/**
 * Ayarlar Modeli
 * 
 * @author atolyemed.com
 * */

class Config_model extends CI_Model {


    
    /**
     * belli bir ayar çekiliyor
     * */
     
     public function getConfig($key=false)
     {
        if(!$key)
        {
            return false;
        }
        
        $this->db->where('config_key',$key);
        $query = $this->db->get('config');
        
        if ($query->num_rows() > 0) {
            
            return $query->row()->config_value;
        }
        
        return 0;
        
     }
     
     /**
      * Ayar kaydediliyor
      * 
      * */
      
      public function saveConfig($key,$value)
      {
        
        $this->db->where('config_key',$key);
        $query = $this->db->get('config');
        
        if ($query->num_rows() > 0) {
            
            $config =  $query->row();
            
            
            $dataa['config_value'] = $value;
            
            $this->db->update('config',$dataa,array('id' => $config->id));
            
        }else
        {
            
            $dataa['config_key'] = $key;
            $dataa['config_value'] = $value;
            
            $this->db->insert('config',$dataa);
        }
        
      }


}