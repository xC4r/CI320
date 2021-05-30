<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
ini_set('memory_limit', '-1');
set_time_limit(0);

class Main extends CI_Controller
{   //private $codUser;
    //private $desUser;
    function __construct(){
        parent::__construct();
        $this->load->helper('constants_helper');
        $this->load->helper('functions_helper');
        $this->codUser = $this->session->userdata('codUser'); 
        $this->desUser = $this->session->userdata('desUser');
        if ($this->codUser == "") {
            header('location: login');
        }
    }
    public function index() {    
        $this->load->view('main');
    }
    public function modulo(){
        //$post = json_decode(file_get_contents('php://input'),true);
        //$modu = $post['mod'];
        $modu = $this->input->get('mod', TRUE); 
        $query = $this->db->get_where('syst_asignamod', array('cod_usuario' => $this->codUser,'cod_ruta' => $modu));
        $html;
        if($query->num_rows() > 0){
            $html = $this->load->view($modu.'/view','',true);
            $html .= $this->load->view('comunForms','',true);
            $jsPath = rtrim("application/modules/".$modu."/assets", '/');
            $jsFiles = glob("{".$jsPath."/*.js}", GLOB_BRACE);
            for($i = 0; $i < count($jsFiles); $i++){
                $html .= '<script src="'.$jsFiles[$i].'"></script>';
            }
        }else{
            $html = 'No Autorizado';
        }
        echo $html;
    }
    public function menu(){
        $json = array('menu' => array(), 'mensaje' => '');
        if($this->codUser ==''){
            $json['mensaje']= 'No hay usuario de session';
        }else{
            $asignados = $this->db->get_where('syst_asignamod', array('cod_usuario' => $this->codUser,'ind_del'=>REGISTRO_NO_ELIMINADO));
            $listMenu = [];
            foreach( $asignados->result_array() as $row ){
                $code = explode('_',$row['cod_ruta']);
                $modulo;
                for ($i=0; $i<count($code); $i++){
                    if($i==0){
                        $modulo = $code[$i];
                    }else{
                        $modulo = $modulo.'_'.$code[$i];
                    }
                    $listMenu[] = $modulo;
                }
            }

            $this->db->select('*');
            $this->db->from('sysm_navmenu');
            $this->db->where_in('cod_menu',$listMenu);
            $this->db->order_by('cod_menu');
            $result = $this->db->get();
            $data = $result->result_array();

            if(count($data)>0){
                foreach( $data as $row ) {
                    $item = array(
                        'cod' => mb_strtolower(trim($row['cod_menu']),'UTF-8'),  
                        'nom' => trim($row['des_menu']),
                        'ico' => mb_strtolower(trim($row['des_icono']),'UTF-8'),
                        'des' => mb_strtolower(trim($row['des_larga']),'UTF-8'),
                        'sub' => null
                    );
                    $x = strlen($row['cod_menu']);
                    switch ($x) {
                        case 4:
                            $json['menu'][] = $item;
                            break;
                        case 9:
                            for($j = 0; $j<count($json['menu']); $j++) {
                                if( $json['menu'][$j]['cod'] == substr($row['cod_menu'],0,4) ){
                                    $json['menu'][$j]['sub'][] = $item;
                                }
                            }
                            break;
                        case 14:
                            for($j = 0; $j<count($json['menu']); $j++) {
                                for($k = 0; $k<count($json['menu'][$j]['sub']); $k++) {     
                                    if( $json['menu'][$j]['sub'][$k]['cod'] == substr($row['cod_menu'],0,9) ) {
                                        $json['menu'][$j]['sub'][$k]['sub'][] = $item;
                                    }
                                } 
                            }                     
                            break;
                        case 19:
                            for($j = 0; $j<count($json['menu']); $j++) {
                                for($k = 0; $k<count($json['menu'][$j]['sub']); $k++) {
                                    for($l = 0; $l<count($json['menu'][$j]['sub'][$k]['sub']); $l++) {     
                                        if( $json['menu'][$j]['sub'][$k]['sub'][$l]['cod'] == substr($row['cod_menu'],0,14) ) {
                                            $json['menu'][$j]['sub'][$k]['sub'][$l]['sub'][] = $item;
                                        }
                                    }    
                                } 
                            }                     
                            break;
                        case 24:
                            for($j = 0; $j<count($json['menu']); $j++) {
                                for($k = 0; $k<count($json['menu'][$j]['sub']); $k++) {
                                    for($l = 0; $l<count($json['menu'][$j]['sub'][$k]['sub']); $l++) {    
                                        for($m = 0; $m<count($json['menu'][$j]['sub'][$k]['sub'][$l]['sub']); $m++) {
                                            if($json['menu'][$j]['sub'][$k]['sub'][$l]['sub'][$m]['cod'] == substr($row['cod_menu'],0,19)) {
                                               $json['menu'][$j]['sub'][$k]['sub'][$l]['sub'][$m]['sub'][] = $item;
                                            }
                                        }
                                    }    
                                } 
                            }                     
                            break;
                        case 29:
                            for($j = 0; $j<count($json['menu']); $j++) {
                                for($k = 0; $k<count($json['menu'][$j]['sub']); $k++) {
                                    for($l = 0; $l<count($json['menu'][$j]['sub'][$k]['sub']); $l++) {    
                                        for($m = 0; $m<count($json['menu'][$j]['sub'][$k]['sub'][$l]['sub']); $m++) {
                                            for($n = 0; $n<count($json['menu'][$j]['sub'][$k]['sub'][$l]['sub'][$m]['sub']); $n++) {
                                                if($json['menu'][$j]['sub'][$k]['sub'][$l]['sub'][$m]['sub'][$n]['cod'] == substr($row['cod_menu'],0,24)) {
                                                   $json['menu'][$j]['sub'][$k]['sub'][$l]['sub'][$m]['sub'][$n]['sub'][] = $item;
                                                }
                                            }
                                        }
                                    }    
                                } 
                            }                     
                            break;
                        default:
                            break;     
                    }
                }
            }
        }            
        echo json_encode($json);
    }
}