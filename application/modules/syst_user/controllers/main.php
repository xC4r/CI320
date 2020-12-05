<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
ini_set('memory_limit', '-1');
set_time_limit(0);

class Main extends MX_Controller {
    private $codi_usua;
    private $nomb_usua;
    public function __construct() {
        parent::__construct();
        $this->load->helper('constants_helper');
        $this->load->helper('functions_helper');
        $this->codi_usua = $this->session->userdata('codi_usua'); 
        $this->nomb_usua = $this->session->userdata('nomb_usua');
        if ($this->codi_usua == "") {
            header('location: login');
        }
    }
    public function syst_user_test(){
        $json = array('cod' => '','msg' => '','res' => array());
        $post = $this->input->post();
        $json['msg'] = $post['txtNombres'];
        /*
        $this->output
        ->set_status_header(401)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        ->_display();
        */

        //http_response_code(401); //Opcional
        //header('Content-Type: application/json','utf-8'); //Opcional
        echo json_encode($json);
    }

    public function syst_user_load(){
        $json = array('cod' => '','msg' => '','res' => array());
        //$post = json_decode(file_get_contents('php://input'),true);
        $headers = $this->input->request_headers();
        //$text = trim($post['data']);
        $this->load->library('Auth');  
        if($this->auth->verifyToken($headers['token']) == false){
                    $json['cod'] = 401;
                    $json['msg'] = 'Sesion Expirada';
                    $this->session->sess_destroy();
        }else{
            try {
                $json['res']['lst_user'] = $this->lista_usuario();
                $json['res']['lst_empresa'] = $this->lista_empresa();
                $json['res']['lst_rol'] = $this->lista_rol();
                $json['res']['lst_estado'] = GET_LST_STATE();
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = 'Error:'.$e->getMessage().'\n';
            }
        }
        echo json_encode($json);
    }

    public function syst_user_list() {
        $json = array('cod' => '','msg' => '','res' => array());
        $post = json_decode(file_get_contents('php://input'),true);
        $text = trim($post['data']); 
        if($text ==''||strlen($text)<3){
            if($text ==''){
                $json['mensaje']= 'No hay descripción para buscar';
            }else{
                $json['mensaje']= 'La descripción es muy corta';
            }
        }else{
            try {
                $json['res']['lst_user'] = $this->lista_usuario($text);
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = 'Error:'.$e->getMessage().'\n';
            }
        }
        echo json_encode($json);       
    }

    public function syst_user_regi() {
        $json = array('cod' => '','msg' => '','res' => array());
        $post = json_decode(file_get_contents('php://input'),true);
        try {
            $data = array(
            'cod_usuario' => $post['json']['cod'],
            'pas_usuario' => $post['json']['pas'],
            'dir_correo' => $post['json']['eml'],
            'des_nombre' => $post['json']['nom'],
            'num_documento' => $post['json']['doc'],
            'num_empresa' => $post['json']['emp'],
            'num_rol' => $post['json']['rol'],
            'cod_estado' => $post['json']['est']
            );
            if($post['json']['num']){
                $data['num_usuario'] = $post['json']['num'];
            }
            $this->db->replace('sysm_usuario',$data);
            $json['res']['lst_user'] = $this->lista_usuario($post['txt']);
            $json['cod'] = 200;
            $json['msg'] = 'Ok';
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        echo json_encode($json);
    }

    public function syst_user_dele() {
        $json = array('cod' => '','msg' => '','res' => array());
        $post = json_decode(file_get_contents('php://input'),true);
        try {
            $this->db->update('sysm_usuario', array('ind_del' => 1), array('num_usuario' => $post['del'],'num_documento'=>$post['doc']));
            $json['res']['lst_user'] = $this->lista_usuario($post['txt']);
            $json['cod'] = 200;
            $json['msg'] = 'Ok';
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        echo json_encode($json);
    }

    private function lista_usuario($text='default') {     
        if($text == 'default'){
            $this->db->limit(20);
            $this->db->select('*');
            $this->db->from('sysm_usuario');
            $this->db->where('ind_del','0'); //No eliminados
            $this->db->order_by('des_nombre', 'ASC');
        }else{
            $this->db->limit(50);
            $this->db->select('*');
            $this->db->from('sysm_usuario');  
            $this->db->where("UPPER(CONCAT_WS('|',cod_usuario,des_nombre,dir_correo,num_documento)) LIKE UPPER('%".$text."%')");
            $this->db->and('ind_del','0'); //No eliminados  
            $this->db->order_by('des_nombre', 'ASC');
        }
        $array = array();     
        $query = $this->db->get();
        $data = [];
        foreach( $query->result_array() as $row ){
            $data[] = $row;
        }
        if(count($data)>0){
            foreach( $data as $row ) {
                $item = array(
                    'num' => mb_strtolower(trim($row['num_usuario']),'UTF-8'),
                    'cod' => mb_strtolower(trim($row['cod_usuario']),'UTF-8'),
                    'pas' => trim($row['pas_usuario']),
                    'eml' => mb_strtolower(trim($row['dir_correo']),'UTF-8'),
                    'nom' => trim($row['des_nombre']),
                    'doc' => mb_strtolower(trim($row['num_documento']),'UTF-8'),
                    'emp' => trim($row['num_empresa']),
                    'rol' => trim($row['num_rol']),
                    'est' => trim($row['cod_estado'])
                );   
                $array[] = $item;
            }
        }        
        return $array;
    }

    private function lista_empresa() {
        $array = array();     
        $this->db->select('*');
        $this->db->from('sysm_empresa');
        $this->db->where('ind_del','0'); //Estado Activo
        $this->db->order_by('des_empresa', 'ASC');
        $query = $this->db->get();
        $data = [];
        foreach( $query->result_array() as $row ){
            $data[] = $row;
        }
        if(count($data)>0){
            foreach( $data as $row ) {
                $item = array(
                    'num' => mb_strtolower(trim($row['num_empresa']),'UTF-8'),
                    'des' => trim($row['des_empresa'].' - '.$row['ruc_empresa'])
                );   
                $array[] = $item;
            }
        }        
        return $array;
    }

    private function lista_rol() {
        $array = array();     
        $this->db->select('*');
        $this->db->from('sysm_rolusua');
        $this->db->where('ind_del','0'); //Estado Activo
        $this->db->order_by('des_rol', 'ASC');
        $query = $this->db->get();
        $data = [];
        foreach( $query->result_array() as $row ){
            $data[] = $row;
        }
        if(count($data)>0){
            foreach( $data as $row ) {
                $item = array(
                    'num' => mb_strtolower(trim($row['num_rol']),'UTF-8'),
                    'des' => mb_strtolower(trim($row['des_rol']),'UTF-8')
                );   
                $array[] = $item;
            }
        }        
        return $array;
    }

    /*
    public function syst_admi_user_data() {
        $json = array('data' => array(), 'mensaje' => '');
        $data['id_usua'] = trim($_REQUEST['usua_data']);
        $this->load->model('syst');
        $data = $this->syst->user_data($data);
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $json['data'][] = array(
                    'id_usua'   => trim($data[$i]->id_user),
                    'codi_usua' => trim($data[$i]->codi_usua), 
                    'pass_usua' => trim($data[$i]->pass_usua),
                    'mail_usua' => trim($data[$i]->mail_usua), 
                    'nomb_usua' => trim($data[$i]->nomb_usua),
                    'apel_usua' => trim($data[$i]->apel_usua),
                    'docu_usua' => trim($data[$i]->docu_usua), 
                    'role_usua' => trim($data[$i]->role_usua),
                    'empr_usua' => trim($data[$i]->codi_empr),
                    'esta_usua' => trim($data[$i]->esta_usua)
                );                 
            }
        }else{
             $json['mensaje'] = "Sin Datos de Usuario";
        }

        echo json_encode($json);
    } 
    */

    /*
    public function syst_admi_user_save() {
        $json = array('data' => array(), 'mensaje' => '');

        $data['nomb_usua'] = trim($_REQUEST['nomb_usua']);
        $data['apel_usua'] = trim($_REQUEST['apel_usua']);
        $data['docu_usua'] = mb_strtolower(trim($_REQUEST['docu_usua']),'UTF-8');
        $data['mail_usua'] = mb_strtolower(trim($_REQUEST['mail_usua']),'UTF-8');
        $data['codi_empr'] = mb_strtolower(trim($_REQUEST['empr_usua']),'UTF-8');
        $data['codi_usua'] = mb_strtolower(trim($_REQUEST['codi_usua']),'UTF-8');
        $data['pass_usua'] = trim($_REQUEST['pass_usua']);
        $data['role_usua'] = mb_strtolower(trim($_REQUEST['role_usua']),'UTF-8');
        $data['esta_usua'] = mb_strtoupper(trim($_REQUEST['esta_usua']),'UTF-8');
        $data['user_acti'] = $this->codi_usua;
        $id_usua = trim($_REQUEST['iden_usua']);
        $query_result = true;
        $this->load->model('syst');

        if(count(array_filter($data)) == count($data))
        {
            if(!preg_match('/^[A-Za-zñÑ\s]{0,30}$/',$data['nomb_usua'])){
                $json['mensaje'] .= "* Los nombres deben contener solo letras [1-30]<br>";
            }
            if(!preg_match('/^[A-Za-zñÑ\s]{0,30}$/',$data['apel_usua'])){
                $json['mensaje'] .= "* Los apellidos deben contener solo letras [1-30]<br>";
            }        
            if(!preg_match('/^[0-9]{8,12}$/',$data['docu_usua'])){
                $json['mensaje'] .= "* El documento debe contener solo numeros [8-12] digitos sin espacios<br>";
            }       
            if(!filter_var($data['mail_usua'],FILTER_VALIDATE_EMAIL)) {
                $json['mensaje'] .= "* El Email no es valido<br>";
            }
            if(!preg_match('/^[A-Za-z0-9\_]{1,20}$/',$data['codi_empr'])){
                $json['mensaje'] .= "* La Empresa no valida<br>"; 
            }       
            if(!preg_match('/^[a-z\_]{1,20}$/',$data['codi_usua'])){
                $json['mensaje'] .= "* El usuario deben contener solo letras separadas por (_) sin espacios [1-20]<br>";
            }    
            if(!preg_match('/^[A-Za-z0-9\-\_\+\*]{6,20}$/',$data['pass_usua'])){
                $json['mensaje'] .= "* La contraseña no deben contener espacios, ni simbolos especiales [6-20]<br>";
            }
            if(!preg_match('/^[A-Z]{1,6}$/',$data['esta_usua'])){
                $json['mensaje'] .= "* El estado no es valido [1-6]<br>";
            }              
        }else{
            $json['mensaje'] = "* Debe llenar todos los campos";  
        }

        if($json['mensaje']==""){
            $vali_usua = $this->syst->user_codi($data['codi_usua']);
            $vali_docu = $this->syst->user_docu($data['docu_usua']);
            if(empty($id_usua)){
                if(count($vali_usua)>0){
                    $json['mensaje'] = "* El usuario ya existe";                  
                }else{
                    if(count($vali_docu)>0){
                        $json['mensaje'] = "* El documento ya existe";  
                    }else{
                        $query_result = $this->syst->user_save($data);
                    }
                }
            }else{
                if(count($vali_usua)>0 && $id_usua != $vali_usua[0]->id_user ){
                    $json['mensaje'] = "* El usuario ya existe";                  
                }else{
                    if(count($vali_docu)>0 && $id_usua != $vali_docu[0]->id_user){
                        $json['mensaje'] = "* El documento ya existe";  
                    }else{
                        $query_result = $this->syst->user_upda($data,$id_usua);
                    }
                }
                    
            }
        }

        if(!$query_result){
            $json['mensaje'] = "No se registró! (Reportar al Admnistrador del Sistema)";    
        }
        echo json_encode($json);
    }
    */

    public function syst_admi_user_dele() {
        $json = array('data' => array(), 'mensaje' => '');
        $data['id_usua'] = trim($_REQUEST['id_usua']);
        $data['user_acti'] = $this->codi_usua;
        $this->load->model('syst');
        if(!$this->syst->user_dele($data)){
            $json['mensaje'] = "No se eliminó! (Reportar al Admnistrador del Sistema)";  
        }
        echo json_encode($json);
    }
    public function syst_admi_user_regi_empr_list(){
        $json = array('data' => array(), 'mensaje' => '');
        $this->load->model('syst');
        $data = $this->syst->user_empr();
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $json['data'][] = array(
                    'codi_empr' => trim($data[$i]->codi_empr),
                    'nomb_empr' => trim($data[$i]->nomb_empr)
                );
            }
        }else{
             $json['mensaje'] = "Sin Empresas";
        }

        echo json_encode($json);       
    }
    public function syst_admi_user_regi_role_list(){
        $json = array('data' => array(), 'mensaje' => '');
        $this->load->model('syst');
        $data = $this->syst->user_role();
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $json['data'][] = array(
                    'codi_role' => trim($data[$i]->codi_codi),
                    'desc_role' => trim($data[$i]->desc_codi)
                );
            }
        }else{
             $json['mensaje'] = "Sin Empresas";
        }

        echo json_encode($json);       
    }
    public function syst_admi_role_list() {
        $json = array('data' => array(), 'mensaje' => '');
        $this->load->model('syst');
        $data = $this->syst->role_list();
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $json['data'][] = array(
                    'codi_codi' => trim($data[$i]->codi_codi),
                    'desc_codi' => trim($data[$i]->desc_codi), 
                    'esta_codi' => trim($data[$i]->esta_codi),
                    'fech_modi' => trim($data[$i]->fech_modi),
                    'user_acti' => trim($data[$i]->user_acti)
                );
            }
        }else{
             $json['mensaje'] = "Sin Usuarios";
        }
        echo json_encode($json);
    }
    public function syst_admi_role_data() {
        $json = array('data' => array(), 'mensaje' => '');
        $data['codi_role'] = trim($_REQUEST['role']);
        $this->load->model('syst');
        $data = $this->syst->role_data($data['codi_role']);
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $json['data'][] = array(
                    'role_codi' => trim($data[$i]->codi_codi), 
                    'desc_codi' => trim($data[$i]->desc_codi),
                    'esta_codi' => trim($data[$i]->esta_codi)
                );                 
            }
        }else{
             $json['mensaje'] = "Sin Datos de Rol";
        }
        echo json_encode($json);
    }
    public function syst_admi_role_save() {
        $json = array('data' => array(), 'mensaje' => '');

        $data['role_codi'] = mb_strtolower(trim($_REQUEST['role_codi']),'UTF-8');
        $data['desc_codi'] = trim($_REQUEST['desc_codi']);
        $data['esta_codi'] = mb_strtoupper(trim($_REQUEST['esta_codi']),'UTF-8');
        $data['user_acti'] = $this->codi_usua;
        $disa_esta = filter_var($_REQUEST['disa_codi'], FILTER_VALIDATE_BOOLEAN);
        $query_result = true;
        $this->load->model('syst');

        if(count(array_filter($data)) == count($data))
        {
            if(!preg_match('/^[A-Za-z0-9\_]{1,20}$/',$data['role_codi'])){
                $json['mensaje'] .= "* El codigo de Rol no es valido [1-20]<br>"; 
            }
            if(!preg_match('/^[A-Za-zñÑ\s]{1,30}$/',$data['desc_codi'])){
                $json['mensaje'] .= "* La descripcion deben contener solo letras [1-30]<br>";
            }                     
            if(!preg_match('/^[A-Z]{1,6}$/',$data['esta_codi'])){
                $json['mensaje'] .= "* El estado no es valido [1-6]<br>";
            }    
        }else{
            $json['mensaje'] = "* Debe llenar todos los campos";  
        }

        if($json['mensaje']==""){
            $vali_role = $this->syst->role_data($data['role_codi']);
            //die($data['disa_codi']);
            if(!$disa_esta){
                if(count($vali_role)>0){
                    $json['mensaje'] = "* El Rol ya existe";              
                }else{
                    //$query_result = this->syst->role_save($data); 
                }
            }else{
                if(count($vali_role)>0){
                    //$query_result = this->syst->role_upda($data);             
                }else{
                    $json['mensaje'] = "* Rol no encontrado";
                }     
            }
        }

        if(!$query_result){
            $json['mensaje'] = "No se registró! (Reportar al Admnistrador del Sistema)";    
        }
        echo json_encode($json);
    }
    /*
    public function validar_numeros($cadena){
        $caracteres = "0123456789"; 
        $validez = 1; 
        for ($i=0; $i<=strlen($cadena)-1; $i++) { 
            if (strpos($caracteres,substr($cadena,$i,1))===false) {
                $validez = 0;
            } 
        }
        return $validez;    
    } 
    public function validar_array($array){
        $validez = 1; 
        for ($i=0; $i<count($array); $i++) {
            $keys = array_keys($array);
            if ($array[$keys[$i]]=="") {
                $validez = 0;
            } 
        }
        return $validez;    
    }
    */
    
}
