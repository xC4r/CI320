<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
ini_set('memory_limit', '-1');
set_time_limit(0);

class Main extends MX_Controller {
    private $codUser;
    private $desUser;
    public function __construct() {
        parent::__construct();
        $this->load->helper('constants_helper');
        $this->load->helper('functions_helper');
        $this->codUser = $this->session->userdata('codUser'); 
        $this->desUser = $this->session->userdata('desUser');
        if ($this->codUser == "") {
            header('location: login');
        }
    }
    public function test01(){
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
    public function defaultLoad(){
        $json = array('cod' => '','msg' => '','res' => array());
        $headers = $this->input->request_headers();
        $this->load->library('Auth');  
        if($this->auth->verifyToken($headers['token']) == false){
                    $json['cod'] = 401;
                    $json['msg'] = 'Sesion Expirada';
                    $this->session->sess_destroy();
        }else{
            try {
                $json['res']['lstUser'] = $this->listaUsuario();
                $json['res']['lstEmpresa'] = $this->listaEmpresa();
                $json['res']['lstRol'] = $this->listaRol();
                $json['res']['lstEstado'] = GET_LST_STATE();
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = 'Error:'.$e->getMessage().'\n';
            }
        }
        echo json_encode($json);
    }
    public function cargarUsuarios(){
        $json = array('cod' => '','msg' => '','res' => array());
        $text = trim($this->input->get('txt', TRUE)); 
        if($text ==''||strlen($text)<3){
            if($text ==''){
                $json['msg']= 'No hay descripción para buscar';
            }else{
                $json['msg']= 'La descripción es muy corta';
            }
        }else{
            try {
                $json['res']['lstUser'] = $this->listaUsuario($text);
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = 'Error:'.$e->getMessage().'\n';
            }
        }
        echo json_encode($json);       
    }
    public function operacion(){
        $json = array('cod' => '','msg' => '','res' => array());
        $post = $this->input->post(NULL, TRUE);
        $requestPrototype = array(
            'ID' => null,
            'Serial' => '',
            'codUsuario' => '',
            'desUsuario' => '',
            'docUsuario' => '',
            'fecha' => null,
            'actividad' => '',
            'formulario' => '',
            'ipaddress' => '',
            'address' => '',  
            'xcoords' => ''
        );
        if(!count(array_diff_key($requestPrototype,$post))>0){
            if($post['codUsuario']!=NULL && !empty($post['codUsuario']) && $post['docUsuario']!=NULL && !empty($post['docUsuario']) && !empty($post['formulario']) && $post['formulario']!=NULL){
                try {
                    $dataArray = json_decode(str_replace('\\','',$post['formulario']), true);
                    if(isset($dataArray['action'])&&$dataArray['action'] == 1){
    


                    }else if(isset($dataArray['action'])&&$dataArray['action'] =='2'){
                        if($dataArray!=NULL){
                            $json = $this->actualizarUsuario($dataArray,$json);
                        }
                    }else if(isset($dataArray['action'])&&$dataArray['action'] =='0'){
    
    
                    }else{
                        $json['cod'] = 204;
                        $json['msg'] = 'Error datos invalidos';
                    }
                } catch (Exception $e) {
                    $json['cod'] = 203;
                    $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
                }
            }else{
                $json['cod'] = 201;
                $json['msg'] = 'Error operacion invalida';
            }
        }else{
            $json['cod'] = 201;
            $json['msg'] = 'Request Error';
        }
        echo json_encode($json);
    }

    private function registroUsuario(){
        $json = array('cod' => '','msg' => '','res' => array());
        $post = $this->input->post(NULL, TRUE);
        //print_r($post);
        //echo $post['txtNombres'];
        try {
            $data = array(
            'cod_usuario' => $post['txtUsuario'],
            'pas_usuario' => $post['txtPassword'],
            'dir_correo' => $post['txtCorreo'],
            'des_nombre' => $post['txtNombres'],
            'num_documento' => $post['txtDocumento'],
            'num_empresa' => $post['txtEmpresa'],
            'num_rol' => $post['txtRol'],
            'cod_estado' => $post['txtEstado']
            );
            /*
            if($post['json']['num']){
                $data['num_usuario'] = $post['json']['num'];
            }
            */
            $this->db->replace('sysm_usuario',$data);
            $json['res']['lstUser'] = $this->listaUsuario($post['txt']);
            $json['cod'] = 200;
            $json['msg'] = 'Ok';
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        echo json_encode($json);
    }
    private function actualizarUsuario($data,$json){
        try {
            if($this->codUser == 'usuario_maestro'|| $this->codUser == $data['txtUsuario']|| $this->codUser == 'supervisor_del_usuario'){
                $this->db->where('num_documento',$data['txtDocumento']);
                $this->db->where_not_in('cod_usuario',$data['txtUsuario']);
                $query = $this->db->get('sysm_usuario');
                if($query->num_rows()>0){
                    $json['cod'] = 204;
                    $json['msg'] = 'El documento ya se encuentra en uso';
                }else{
                    $update = array(
                        'pas_usuario' => $data['txtPassword'],
                        'dir_correo' => $data['txtCorreo'],
                        'des_nombre' => $data['txtNombres'],   
                        'num_documento' => $data['txtDocumento'],
                        'num_empresa' => $data['txtEmpresa'],
                        'num_rol' => $data['txtRol'],
                        'cod_estado' => $data['txtEstado']
                    );
                    $this->db->where('cod_usuario', $data['txtUsuario']);
                    $this->db->update('sysm_usuario', $update);
                    $json['res']['lstUser'] = $this->listaUsuario($data['txt']);
                    $json['cod'] = 200;
                    $json['msg'] = 'Ok';
                }
            }else{
                $json['cod'] = 201;
                $json['msg'] = 'No autorizado';
            }
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        return $json;
    }
    private function desactivarUsuario(){
        $json = array('cod' => '','msg' => '','res' => array());
        $post = json_decode(file_get_contents('php://input'),true);
        try {
            $this->db->update('sysm_usuario', array('ind_del' => 1), array('num_usuario' => $post['del'],'num_documento'=>$post['doc']));
            $json['res']['lstUser'] = $this->listaUsuario($post['txt']);
            $json['cod'] = 200;
            $json['msg'] = 'Ok';
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        echo json_encode($json);
    }
    private function listaUsuario($text='default') {     
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
            $this->db->where('ind_del','0'); //No eliminados  
            $this->db->order_by('des_nombre','ASC');
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
    private function listaEmpresa() {
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
    private function listaRol() {
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
