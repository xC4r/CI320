<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}
class Login extends CI_Controller {

    public function __construct() 
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

        $result = $this->db->get_where('sysm_usuario',array('cod_usuario'=>$data['username'],'pas_usuario'=>$data['password'],'cod_estado'=>REGISTRO_ACTIVO));
        if (count($result->result_array()) == 0) {
            $json['cod'] = 401;
            $json['msg'] = "Error usuario y/o clave incorrectos.";
        } else {
            $userData = [];
            foreach($result->result_array() AS $row) {
                $query = $this->db->get_where('sysm_empresa',array('num_ruc' => $row['num_ruc']));
                if (count($query->result_array()) == 0) {
                    $json['cod'] = 401;
                    $json['msg'] = "Error en datos del usuario";
                }else{
                    $empresa = $query->result_array();
                    $userData = array(
                        'codUser'  => trim(mb_strtolower($row['cod_usuario'],'UTF-8')),
                        'desUser'  => trim($row['des_nombre']),
                        'desMail'  => trim(mb_strtolower($row['dir_correo'],'UTF-8')),
                        'numDocu'  =>  trim(mb_strtolower($row['num_documento'],'UTF-8')),
                        'codEmpr'  =>  trim(mb_strtolower($empresa[0]['cod_empresa'],'UTF-8')),
                        'rucEmpr'  =>  trim($empresa[0]['num_ruc']),
                        'desEmpr'  =>  trim($empresa[0]['des_empresa']),
                        'desCome'  =>  trim($empresa[0]['des_comercial']),
                        'dirEmpr'  =>  trim($empresa[0]['dir_empresa']),
                        'tlfEmpr'  =>  trim($empresa[0]['des_telefono'])
                    );
                }
            }
            if($json['cod'] == 401){
                $json['cod'] = 401;
                $json['msg'] = "Error en datos del usuario";
            }else{
                $this->load->library('Auth');
                $json['cod'] = 200;
                $json['msg'] = "Ok";
                $this->session->set_userdata($userData);
                
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json))->
            set_status_header($json['cod']);
    }
    
}


