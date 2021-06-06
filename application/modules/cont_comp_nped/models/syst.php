<?php
class Syst extends CI_Model {
    function __construct(){
        parent::__construct(); 
    }
    function user_list(){
        $query  = "SELECT * FROM master_user ORDER BY fech_modi DESC LIMIT 5";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function user_empr(){
        $query  = "SELECT codi_empr,nomb_empr FROM master_empr WHERE esta_empr='ACT' ";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function user_role(){
        $query  = "SELECT codi_codi,desc_codi FROM table_cate_codi WHERE codi_cate='role_usua' AND esta_codi='ACT' ORDER BY 1";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function user_data($data){
    	$id_usua = $data['id_usua'];
        //$codUser = $data['codUser'];
        $query  = "SELECT * FROM master_user WHERE id_user='$id_usua' ";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function user_codi($codUser){
        $query  = "SELECT id_user FROM master_user WHERE codUser='$codUser' ";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function user_docu($numDoc){
        $query  = "SELECT id_user FROM master_user WHERE numDoc='$numDoc' ";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function user_upda($data,$id_usua){
        //$codUser = $data['codUser'];
        $pass_usua = $data['pass_usua'];
        $mail_usua = $data['mail_usua'];
        $desUser = $data['desUser'];
        $apel_usua = $data['apel_usua'];
        $numDoc = $data['numDoc'];
        $role_usua = $data['role_usua'];
        $codi_empr = $data['codi_empr'];
        $esta_usua = $data['esta_usua'];
        $user_acti = $data['user_acti'];
        $query  = "UPDATE master_user SET pass_usua='$pass_usua',mail_usua='$mail_usua',
                    desUser='$desUser',apel_usua='$apel_usua',numDoc='$numDoc', 
                    role_usua='$role_usua',codi_empr='$codi_empr',esta_usua='$esta_usua',
                    user_acti='$user_acti' 
                    WHERE id_user='$id_usua' ";
        $stm = $this->db->query($query);
        return $stm;
    }
    function user_save($data){
        $codUser = $data['codUser'];
        $pass_usua = $data['pass_usua'];
        $mail_usua = $data['mail_usua'];
        $desUser = $data['desUser'];
        $apel_usua = $data['apel_usua'];
        $numDoc = $data['numDoc'];
        $role_usua = $data['role_usua'];
        $codi_empr = $data['codi_empr'];
        $esta_usua = $data['esta_usua'];
        $user_acti = $data['user_acti'];
        $query  = "INSERT INTO master_user(codUser,pass_usua,mail_usua,desUser,apel_usua,
                                           numDoc,role_usua,codi_empr,esta_usua,user_acti) 
                   VALUES ('$codUser','$pass_usua','$mail_usua','$desUser','$apel_usua',
                           '$numDoc','$role_usua','$codi_empr','$esta_usua','$user_acti') ";        
        $stm = $this->db->query($query);
        return $stm;
    }
    function user_dele($data){
        $id_usua = $data['id_usua'];
        $user_acti = $data['user_acti'];
        $query  = "UPDATE master_user SET esta_usua='DEL',user_acti='$user_acti' 
                   WHERE id_user='$id_usua' ";
        $stm = $this->db->query($query);
        return $stm;
    }         

    function role_list(){
        $query = "SELECT * FROM table_cate_codi WHERE codi_cate='role_usua' ORDER BY 2";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function role_data($role_codi){
        //$codi_role = $data['role_codi'];
        $query = "SELECT * FROM table_cate_codi WHERE codi_codi='$role_codi' AND codi_cate='role_usua' LIMIT 1";        
        $stm = $this->db->query($query);
        return $stm->result();
    }
    function role_upda($data){

        $role_codi = $data['role_codi'];
        $desc_codi = $data['desc_codi'];
        $esta_codi = $data['esta_codi'];
        $user_acti = $data['user_acti'];
        $query  = "UPDATE table_cate_codi SET desc_codi='$desc_codi',esta_codi='$esta_codi', user_acti='$user_acti', 
                   WHERE codi_cate='role_usua' AND codi_codi='$role_codi' ";
        $stm = $this->db->query($query);
        return $stm;
    }
    function role_save($data){
        $role_codi = $data['role_codi'];
        $desc_codi = $data['desc_codi'];
        $esta_codi = $data['esta_codi'];
        $user_acti = $data['user_acti'];
        $query  = "INSERT INTO table_cate_codi(codi_cate,codi_codi,desc_codi, esta_codi, user_acti) 
                   VALUES ('role_usua','$role_codi','$desc_codi','$esta_codi','$user_acti') ";        
        $stm = $this->db->query($query);
        return $stm;
    }     
    function role_dele($data){
        $role_codi = $data['role_codi'];
        $user_acti = $data['user_acti'];
        $query  = "UPDATE table_cate_codi SET esta_codi='DEL',user_acti='$user_acti' 
                   WHERE codi_cate='role_usua' AND codi_codi='$role_codi' ";
        $stm = $this->db->query($query);
        return $stm;
    }

}