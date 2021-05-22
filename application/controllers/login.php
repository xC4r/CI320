<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
    //private $codUser;
    function __construct() 
    {
        parent::__construct();
        $this->load->helper('constants');
        $this->codUser = $this->session->userdata('codUser');
        if ($this->codUser != "") {
            header('location: main');
        }
    }
    public function index() {
        $this->load->view('index');
    }

    public function logout() {
        $this->session->sess_destroy();
        header('location: ../');
    } 

    public function auth() {
        $json = array('cod'=>'','msg'=>'','res'=>'');
        $data['username'] = $this->input->get('user', TRUE);
        $data['password'] = $this->input->get('pass', TRUE);

        $result = $this->db->get_where('sysm_usuario',array('cod_usuario'=>$data['username'],'pas_usuario'=>$data['password'],'cod_estado'=>REGISTRO_ACTIVO,'ind_del'=>REGISTRO_NO_ELIMINADO));
        if (count($result->result_array()) == 0) {
            $json['cod'] = 401;
            $json['msg'] = "Error usuario y/o clave incorrectos.";
        } else {
            $userData = [];
            foreach($result->result_array() AS $row) {
                $userData = array(
                    'codUser'  => trim(mb_strtolower($row['cod_usuario'],'UTF-8')),
                    'desUser'  => trim(mb_strtolower($row['des_nombre'],'UTF-8')),
                    'desMail'  => trim(mb_strtolower($row['dir_correo'],'UTF-8')),
                    'numDoc'  => trim(mb_strtolower($row['num_documento'],'UTF-8'))
                );
            }
            $this->load->library('Auth');
            $json['cod'] = 200;
            $json['msg'] = "ok";
            $json['res']['tkn'] = $this->auth->validToken($userData);          
            $this->session->set_userdata($userData);
        }
        //echo json_encode($json);
        $this->output->set_content_type('application/json')->set_output(json_encode($json))->set_status_header($json['cod']);
    }
    
}


