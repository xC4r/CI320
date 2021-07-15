
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
        $this->rucEmpr = $this->session->userdata('rucEmpr');
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
                $json['res']['lstNotas'] = $this->listaNotas();
                //$json['res']['lstEmpresa'] = $this->listaEmpresa();
                //$json['res']['lstRol'] = $this->listaRol();
                //$json['res']['lstEstado'] = GET_LST_STATE();
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = 'Error:'.$e->getMessage().'\n';
            }
        }
        echo json_encode($json);
    }
    public function cargarNotasPedido(){
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
                $json['res']['lstNotas'] = $this->listaNotas($text);
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
                    if(isset($dataArray['act'])&&$dataArray['act'] == 1){
                        $json = $this->registrarUsuario($dataArray,$json);

                    }else if(isset($dataArray['act'])&&$dataArray['act'] == 2){
                        $json = $this->actualizarUsuario($dataArray,$json);

                    }else if(isset($dataArray['act'])&&$dataArray['act'] == 0){
                        $json = $this->eliminarUsuario($dataArray,$json);
    
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
    private function registrarUsuario($data,$json){
        try {
            if($this->codUser == 'usuario_maestro'|| $this->codUser == 'supervisor_del_usuario'){
                $this->db->where('num_documento',$data['txtDocumento']);
                $this->db->where('cod_usuario',$data['txtUsuario']);
                $queryPersona = $this->db->get('sysm_usuario');
                $this->db->where('num_documento',$data['txtDocumento']);
                $queryDocumento = $this->db->get('sysm_usuario');
                $this->db->where('cod_usuario',$data['txtUsuario']);
                $queryUsuario = $this->db->get('sysm_usuario');
                if($queryPersona->num_rows()>0){
                    $json['cod'] = 204;
                    $json['msg'] = 'El usuario y documento ya existen';
                }else { 
                    if($queryDocumento->num_rows()>0){
                        $json['cod'] = 204;
                        $json['msg'] = 'El documento esta en uso';
                    }else {
                        if($queryUsuario->num_rows()>0){
                            $json['cod'] = 204;
                            $json['msg'] = 'EL usuario ya existe';
                        }else {
                            $insert = array(
                                'cod_usuario' => $data['txtUsuario'],
                                'pas_usuario' => $data['txtPassword'],
                                'dir_correo' => $data['txtCorreo'],
                                'des_nombre' => $data['txtNombres'],   
                                'num_documento' => $data['txtDocumento'],
                                'num_empresa' => $data['txtEmpresa'],
                                'num_rol' => $data['txtRol'],
                                'cod_estado' => $data['txtEstado']
                            );
                            $this->db->insert('sysm_usuario',$insert);
                            $json['res']['lstNotas'] = $this->listaNotas($data['txt']);
                            $json['cod'] = 200;
                            $json['msg'] = 'Ok';
                        }
                    }
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
                    $json['res']['lstNotas'] = $this->listaNotas($data['txt']);
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
    private function eliminarUsuario($data,$json){
        try {
            if($this->codUser == 'usuario_maestro'|| $this->codUser == 'supervisor_del_usuario'){
                $eliminar = array(
                    'num_empresa' => '1',
                    'cod_estado' => '02',
                    'ind_del' => '1'
                );
                $this->db->where('cod_usuario', $data['txtUsuario']);
                $this->db->update('sysm_usuario', $eliminar);
                $json['res']['lstNotas'] = $this->listaNotas($data['txt']);
                $json['cod'] = 200;
                $json['msg'] = 'Ok';
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
    private function listaNotas($param = []) {   
        $sql = 'SELECT * FROM cont_cpe WHERE num_ruc = '.$this->rucEmpr.' AND cod_cpe ="00"';

        if(isset($param['numSerie']) && !empty($param['numSerie'])){
            $sql .=' AND num_serie = '.$param['numSerie'];
        }
        if(isset($param['numCpe']) && !empty($param['numCpe'])){
            $sql .=' AND num_cpe = '.$param['numCpe'];
        }
        if(isset($param['fecDesde']) && !empty($param['fecDesde']) && isset($param['fecHasta']) && !empty($param['fecHasta'])){
            $sql .=' AND fec_emision BETWEEN DATE('.$param['fecDesde'].') AND DATE('. $param['fecHasta'].')';
        }
        if(isset($param['numDocrec']) && !empty($param['numDocrec'])){
            $sql .=' AND num_docrecep = '.$param['numDocrec'];
        }
        if(isset($param['desNomrec']) && !empty($param['desNomrec'])){
            $sql .=' AND des_nomrecep LIKE= "%'.$param['desNomrec'].'%"';
        } 
        //$this->db->order_by('fec_emision', 'DESC');      
        $query = $this->db->query($sql);
        $array = array();     
        $data = [];
        foreach( $query->result_array() as $reg ){
            $data[] = $reg;
        }
        if(count($data)>0){
            foreach( $data as $row ) {
                $params = array(
                    'num_ruc' => trim($row['num_ruc']),
                    'cod_cpe' => trim($row['cod_cpe']),
                    'num_serie' => trim($row['num_serie']),
                    'num_cpe' => trim($row['num_cpe'])
                );
                $qry = $this->db->get_where('cont_cpedata',$params);
                $items = [];
                $lstItem = array();
                foreach( $qry->result_array() as $res ){
                    $items[] = $res;
                }
                if(count($items)>0){
                    foreach( $items as $line ) {
                        $lstItem[$line['num_item']][] = array(
                            'rub' => trim($line['cod_rubro']),
                            'val' => trim($line['mto_rubro']),
                            'des' => trim($line['des_rubro'])
                        );
                    }
                }
                $docs = array(
                    'cod' => trim($row['cod_cpe']),
                    'ser' => trim($row['num_serie']),
                    'num' => trim($row['num_cpe']),
                    'fec' => trim($row['fec_emision']),
                    'doc' => trim($row['cod_tipdocrec']),
                    'rec' => trim($row['num_docrecep']),
                    'des' => trim($row['des_nomrecep']),
                    'mon' => trim($row['cod_moneda']),
                    'tca' => trim($row['mto_tipocambio']),
                    'vta' => trim($row['mto_totalvta']),
                    'igv' => trim($row['mto_totaligv']),
                    'tot' => trim($row['mto_imptotal']),
                    'obs' => trim($row['des_observa']),
                    'xml' => trim($row['num_xml']),
                    'itm' => $lstItem
                );   
                $array[] = $docs;
            }
        }        
        return $array;
    }
    /*
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
    */
    public function generarPDF() {
        $array = array(
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
        $html = '<html>
            <head>
                <title>HTML con PHP – aprenderaprogramar.com</title>
            </head>
            <body>
                Esto es una página HTML con código PHP incrustado.
                <br />
            </body>
        </html>';
        $this->load->library('fpdf/FPDF');
        $pdf = new FPDF("P","mm",array(58,100));
        $pdf->SetMargins(-1,0,0);
        $pdf->SetAutoPageBreak(true,5);
        $pdf->AddPage();
        $pdf->SetFont('Times','',10);
        //$pdf->Cell(58,5,'DWWDA',1);
        $pdf->Ln(5);
        $pdf->MultiCell(58,4,'MATERIALES DE CONSTRUCCION',0,'C');
        $pdf->SetFontSize(9);
        //$pdf->Cell(58,4,'RUC: 20601344557',0,0,'C');
        //$pdf->Ln();
        $pdf->MultiCell(58,4,'Carr Central km 3.5 - Cayhuayma',0,'C');
        $pdf->Cell(58,4,'Tlf: 962645577 - 962683058',0,0,'C');
        $pdf->SetFontSize(10);
        $pdf->Ln(8);
        $pdf->Cell(58,4,'NOTA DE PEDIDO 0001-0265',0,0,'C');
        $pdf->Ln(8);
        $pdf->Cell(58,4,'Fecha de Emision: 01/06/2021 12.00:01',0,0,'L');
        $pdf->Ln();
        $pdf->Cell(58,4,'Vendedor : tomy_matias',0,0,'L');
        $pdf->Ln();
        $pdf->Cell(58,4,'DNI Cliente : 04548551',0,0,'L');
        $pdf->Ln();
        $pdf->Cell(58,4,'Nombre Cliente : Darlan Gutarra',0,0,'L');
        $pdf->Ln();
        $pdf->Cell(58,4,'Direccion Cliente : Av. Los alisos 12',0,0,'L');
        $pdf->Ln(5);
        $pdf->Cell(7,4,'Cod','TB',0,'L');
        $pdf->Cell(22,4,'Descripcion','TB',0,'L');
        $pdf->Cell(8,4,'Cant','TB',0,'C');
        $pdf->Cell(10.5,4,'P.Unit','TB',0,'C');
        $pdf->Cell(12,4,'Importe','TB',0,'C');
        $pdf->Ln();
        $pdf->Cell(7,4,'001',0,0,'L');
        $pdf->Cell(22,4,'Fierro 1/2 AA',0,0,'L');
        $pdf->Cell(8,4,'25',0,0,'R');
        $pdf->Cell(10.5,4,'28.00',0,0,'R');
        $pdf->Cell(12.5,4,'700.00',0,0,'R');
        $pdf->Ln();
        $pdf->Cell(7,4,'002',0,0,'L');
        $pdf->Cell(22,4,'Fierro 3/8 Acerl',0,0,'L');
        $pdf->Cell(8,4,'10',0,0,'R');
        $pdf->Cell(10.5,4,'15.00',0,0,'R');
        $pdf->Cell(12.5,4,'150.00',0,0,'R');
        $pdf->Ln();
        $pdf->Cell(47.5,4,'TOTAL',1,0,'R');
        $pdf->Cell(12.5,4,'850.00',1,0,'R');
        $pdf->Ln(10);
        $pdf->Cell(58,4,'Gracias por su compra!',0,0,'C');
        $pdf->Ln(10);
        $pdf->SetFontSize(6);
        $pdf->Cell(58,4,'NC v1.0         usuario:fmatias       2021R',0,0,'R');
        $pdf->Ln();
        $pdf->SetFontSize(18);
        $pdf->Cell(58,4,'-',0,0,'L');
        $pdf->Output();
    } 
}
