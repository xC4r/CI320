<?php
class Apli extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function list_apli($codi_usua) {
        $query = "SELECT 
                    a.ruta_apli,
                    a.nive_apli,
                    a.icon_apli,
                    a.titu_apli,
                    a.menu_niv0,
                    m.icon_codi icon_niv0,
                    m.nomb_codi nomb_niv0,
                    a.menu_niv1,
                    n.icon_codi icon_niv1,
                    n.nomb_codi nomb_niv1,
                    a.menu_niv2,
                    o.icon_codi icon_niv2,
                    o.nomb_codi nomb_niv2
                    FROM table_user_apli u
                    INNER JOIN master_apli a ON u.ruta_apli = a.ruta_apli
                    LEFT JOIN table_cata_menu m ON a.menu_niv0 = m.codi_grup
                    LEFT JOIN table_cata_menu n ON a.menu_niv1 = n.codi_grup
                    LEFT JOIN table_cata_menu o ON a.menu_niv2 = o.codi_grup
                    WHERE u.codi_usua='$codi_usua'
                    ORDER BY 1 ";
        
        $sql = $this->db->query($query); 
        /*$array = [];
        foreach ($sql->result_array() as $row){
            $array[] = $row; 
        }*/
        return $sql->result();
    }
    function menu_apli($codi_usua) {
        
        $query = "SELECT cod_ruta FROM syst_asignamod WHERE cod_usuario ='$codi_usua' ORDER BY 1";
        $sql = $this->db->query($query);
        //$result = $sql->result();
        //$query = "SELECT cod_ruta,des_modulo,des_icono FROM sysm_modulo WHERE 1=0";
        $list_menu = [];
        foreach( $sql->result_array() as $row ) {
            $code = explode('_',$row['cod_ruta']);
            for ($i=0; $i < ; $i++){
                if($i=0){
                    $list_menu[] = $code[$i]
                }else{
                    $list_menu[] = $list_menu[$i-1].'_'.$code[$i]
                }
            }
        }

        
            if(isset($mod[0])) {
                $codi_apli = $mod[0];
                $query.= "UNION SELECT ruta_apli,titu_apli,icon_apli FROM master_apli WHERE ruta_apli='$mod[0]'"; 
            }      
            if(isset($mod[1])&&isset($mod[2])) {
                $codi_apli = $mod[0].'_'.$mod[1].'_'.$mod[2];
                $query.= "UNION SELECT ruta_apli,titu_apli,icon_apli FROM master_apli WHERE ruta_apli='$codi_apli' "; 
            }
            if(isset($mod[3])&&isset($mod[4])) {
                $codi_apli = $mod[0].'_'.$mod[1].'_'.$mod[2].'_'.$mod[3].'_'.$mod[4];
                $query.= "UNION SELECT ruta_apli,titu_apli,icon_apli FROM master_apli WHERE ruta_apli='$codi_apli' "; 
            }
            if(isset($mod[5])&&isset($mod[6])) {
                $codi_apli = $mod[0].'_'.$mod[1].'_'.$mod[2].'_'.$mod[3].'_'.$mod[4].'_'.$mod[5].'_'.$mod[6];
                $query.= "UNION SELECT ruta_apli,titu_apli,icon_apli FROM master_apli WHERE ruta_apli='$codi_apli' "; 
            } 
            if(isset($mod[7])&&isset($mod[8])) {
                $codi_apli = $mod[0].'_'.$mod[1].'_'.$mod[2].'_'.$mod[3].'_'.$mod[4].'_'.$mod[5].'_'.$mod[6].'_'.$mod[7].'_'.$mod[8];
                $query.= "UNION SELECT ruta_apli,titu_apli,icon_apli FROM master_apli WHERE ruta_apli='$codi_apli' "; 
            }                       
    
        $sql = $this->db->query($query);
 
        /*$array = [];
        foreach ($sql->result_array() as $row){
            $array[] = $row; 
        }*/
        return $sql->result();
    }    
}