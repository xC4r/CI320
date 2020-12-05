<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
    private $codi_usua;
    function __construct() 
    {
        parent::__construct();
        $this->load->helper('constants');
        $this->codi_usua = $this->session->userdata('codi_usua');
        if ($this->codi_usua != "") {
            header('location: main');
        }
    }
    public function index() {
        $this->load->view('index');
    }

    public function sesi_sali() {
        $this->session->sess_destroy();
        header('location: ../');
    }    
    public function sesi_ingr() {
        $json = array('mensaje' => '','token'=>'');
        // Takes raw data from the request
        $data = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($data);
        //die(print_r($data));
        $data['username'] = trim($data[0]->user);
        $data['password'] = trim($data[0]->pass);

        $result = $this->db->get_where('sysm_usuario',array('cod_usuario'=>$data['username'],'pas_usuario'=>$data['password'],'cod_estado'=>REGISTRO_ACTIVO,'ind_del'=>REGISTRO_NO_ELIMINADO));
        if (count($result) == 0) {
            $json['mensaje'] = "* ERROR USUARIO Y/O CLAVE INCORRECTO.";
        } else {
            $user_data;
            foreach($result->result_array() AS $row) {
                $user_data = array(
                    'codi_usua'  => trim(mb_strtolower($row['cod_usuario'],'UTF-8')),
                    'nomb_usua'  => trim(mb_strtolower($row['des_nombre'],'UTF-8')),
                    'corr_usua'  => trim(mb_strtolower($row['dir_correo'],'UTF-8')),
                    'docu_usua'  => trim(mb_strtolower($row['num_documento'],'UTF-8'))
                );
            }
            $this->load->library('Auth');
            $json['token'] = $this->auth->validToken($user_data);          
            $this->session->set_userdata($user_data);
        }
        echo json_encode($json);
    }
    
    public function sesi_vali() {
        if(isset($user_data)){
            $codi_usua = $this->session->userdata('codi_usua');
            if($codi_usua == 'usuario_maestro'){
                header('location: main');
            }
        }
    }
    
    
}


