<?php


class Group_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    
    /**
     * Tüm Gruplar Çekiliyor
     * 
     * @params $limit int
     * @params $start int
     * */
     
     public function getAllGroups($limit=false, $start=false)
     {
        if($limit)
            $this->db->limit($limit, $start);
            
        $query = $this->db->get("user_groups");
        
         if ($query->num_rows() > 0) {
            
            return $query->result();
        }
        
        return array();
        
     }
     
     /**
      * 
      * Gruplarýn sayýlarý alýnýypr
      * 
      * */
      
      public function getAllCount() {
        return $this->db->count_all("user_groups");
        }
    
    
    /**
     * Kullanýcýnýn grubu ve yetkileri kontrol ediliyor
     * 
     * @param group_id int
     * */
     
     public function getUserGroup($group_id=false)
     {
        if(!$group_id)
        {
            return;
        }
        
        
       
        $sql = "SELECT * FROM user_groups where id = ?";
        $query = $this->db->query($sql, array($group_id));
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        } 
        
        return false;
     }
     
     
     /**
      * Grup Silme Islemi
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
        $this->db->delete('user_groups'); 
        
      }
}


?>
