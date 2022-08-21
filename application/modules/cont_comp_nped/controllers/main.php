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
        $this->desEmpr = $this->session->userdata('desEmpr');
        $this->desCome = $this->session->userdata('desCome');
        $this->dirEmpr = $this->session->userdata('dirEmpr');
        $this->tlfEmpr = $this->session->userdata('tlfEmpr');
        if ($this->codUser == "") {
            header('location: login');
        }
    }
    /*public function funcionBasica(){
        $json = array('cod' => '','msg' => '','res' => array()); 
        $headers = $this->input->request_headers();
        $this->load->library('Auth');  
        if($this->auth->verifyToken($headers['token']) == false){
                    $json['cod'] = 401;
                    $json['msg'] = 'Sesion Expirada';
                    $this->session->sess_destroy();
        }else{
            // Operaciones function
        }   
        echo json_encode($json);
    }*/
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
                $json['res']['lstSeries'] = $this->listaSeries();
                $json['res']['lstProdserv'] = $this->listaProductoServ();
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = 'Error:'.$e->getMessage().'\n';
            }
        }
        echo json_encode($json);
    }
    public function listarNotasPedido(){
        $json = array('cod' => '','msg' => '','res' => array());
        $per = $this->input->get('per', TRUE); 
        $headers = $this->input->request_headers();
        $this->load->library('Auth');  
        if($this->auth->verifyToken($headers['token']) == false){
                    $json['cod'] = 401;
                    $json['msg'] = 'Sesion Expirada';
                    $this->session->sess_destroy();
        }else{
            date_default_timezone_set('America/Lima');
            if($per ==''||strlen($per)!==6||substr($per,0,4)<2000||substr($per,0,4)>date("Y")||substr($per,4,2)>12||substr($per,4,2)<1 ){
                if($per ==''){
                    $json['msg']= 'Periodo vacio';
                }else{
                    $json['msg']= 'Periodo incorrecto';
                }
            }else{
                try {
                    $json['res']['lstNotas'] = $this->listaNotas($per);
                    $json['cod'] = 200;
                    $json['msg'] = "Ok";
                } catch (Exception $e) {
                    $json['cod'] = 204;
                    $json['msg'] = 'Error:'.$e->getMessage().'\n';
                }
            }
        }   
        echo json_encode($json);       
    }
    public function operacion(){
        $json = array('cod' => '','msg' => '','res' => array());
        $post = $this->input->post(NULL, TRUE);
        //$dataArray = str_replace('"[','[',str_replace(']"',']',str_replace('\\','',$post['formulario'])));
        //die($dataArray);
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
                    $dataArray = json_decode(str_replace('"[','[',str_replace(']"',']',str_replace('\\','',$post['formulario']))),true);
                    //die(print_r($dataArray));
                    if(isset($dataArray['ope'])&&$dataArray['ope'] == 1){
                        $json = $this->registrarComprobante($dataArray,$json);
                    }else if(isset($dataArray['ope'])&&$dataArray['ope'] == 2){
                        $json = $this->actualizarComprobante($dataArray,$json);
                    }else if(isset($dataArray['ope'])&&$dataArray['ope'] == '0'){
                        $json = $this->eliminarComprobante($dataArray,$json);
                    }else if(isset($dataArray['ope'])&&$dataArray['ope'] == 'pdf'){
                        $this->generarPdfComprobante($dataArray,$json);
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
    private function registrarComprobante($data,$json){
        try {
            //if($this->codUser == 'usuario_maestro'|| $this->codUser == 'supervisor_del_usuario'){
            //die(print_r($data));
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('cod_cpe','00');
            $this->db->where('num_serie',$data['selSerieCP']);
            $this->db->where('num_cpe',$data['txtNumeroCP']);
            $queryCp = $this->db->get('cont_cpe');
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('cod_cpe','00');
            $this->db->where('num_serie',$data['selSerieCP']);
            $this->db->where('num_cpe',$data['txtNumeroCP']);
            $queryCpDeta = $this->db->get('cont_cpedata');
            if($queryCp->num_rows()>0 || $queryCpDeta->num_rows()>0){
                $json['cod'] = 204;
                $json['msg'] = 'El comprobante y detalle ya existe';
            }else { 
                $insert = array(
                    'num_ruc' => $this->rucEmpr,
                    'cod_cpe' => '00',
                    'num_serie' => $data['selSerieCP'],
                    'num_cpe' => $data['txtNumeroCP'],   
                    'fec_emision' => $data['txtFecha'],
                    'cod_tipdocrec' => 0,
                    'num_docrecep' => $data['txtDocumento'],
                    'des_nomrecep' => $data['txtNombre'],
                    'cod_moneda' => 'PEN',
                    'mto_tipocambio' => 1,
                    'mto_imptotal' => $data['txtTotal']
                );
                $res = $this->db->insert('cont_cpe',$insert);
                if($res){
                    $data['itm'][0] = array();
                    (trim($data['txtDireccion'])!=='') ? $data['itm'][0]['01'] = $data['txtDireccion'] :'';
                    (trim($data['txtObservacion'])!=='') ? $data['itm'][0]['02'] = $data['txtObservacion'] :'';
                    foreach( $data['itm'] as $key1=>$row ) {
                        foreach( $row as $key2=>$rub ) {
                            $item = array(
                                'num_ruc' => $this->rucEmpr,
                                'cod_cpe' => '00',
                                'num_serie' => $data['selSerieCP'],
                                'num_cpe' => $data['txtNumeroCP'],   
                                'num_item' => $key1,
                                'cod_rubro' => $key2,
                                'cod_usumod' => $this->codUser
                            );
                            if(is_numeric($rub)){
                                $item['mto_rubro'] = (float)$rub;
                            }else{
                                $item['des_rubro'] = $rub;
                            }
                            $this->db->insert('cont_cpedata',$item);
                        }      
                    }
                    $json['cod'] = 200;
                    $json['msg'] = 'Ok';
                    $json['res']['lstNotas'] = $this->listaNotas($data['per']);
                }else{
                    $json['cod'] = 201;
                    $json['msg'] = 'No se registro el comprobante';
                }
            }    
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        return $json;
    }
    private function actualizarComprobante($data,$json){
        try {
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('cod_cpe','00');
            $this->db->where('num_serie',$data['selSerieCP']);
            $this->db->where('num_cpe',$data['txtNumeroCP']);
            $queryCp = $this->db->get('cont_cpe');
            if(!$queryCp->num_rows()>0){
                $json['cod'] = 204;
                $json['msg'] = 'El documento no existe';
            }else{
                $cpedb = [];
                foreach( $queryCp->result_array() as $row ){ $cpedb[] = $row;}
                if($cpedb[0]['ind_informado'] == 0){
                    $update = array(   
                        'fec_emision' => $data['txtFecha'],
                        'cod_tipdocrec' => 0,
                        'num_docrecep' => $data['txtDocumento'],
                        'des_nomrecep' => $data['txtNombre'],
                        'cod_moneda' => 'PEN',
                        'mto_tipocambio' => 1,
                        'mto_imptotal' => $data['txtTotal']
                    );
                    $where = array(   
                        'num_ruc' => $this->rucEmpr,
                        'cod_cpe' => '00',
                        'num_serie' => $data['selSerieCP'],
                        'num_cpe' => $data['txtNumeroCP']
                    );
                    $res = $this->db->update('cont_cpe', $update, $where);
                    if($res){
                        $where = array(   
                            'num_ruc' => $this->rucEmpr,
                            'cod_cpe' => '00',
                            'num_serie' => $data['selSerieCP'],
                            'num_cpe' => $data['txtNumeroCP']
                        );
                        $this->db->delete('cont_cpedata',$where);
                        $data['itm'][0]['01'] = $data['txtDireccion'];
                        $data['itm'][0]['02'] = $data['txtObservacion'];
                        foreach( $data['itm'] as $key1=>$row ) {
                            foreach( $row as $key2=>$rub ) {
                                $item = array(
                                    'num_ruc' => $this->rucEmpr,
                                    'cod_cpe' => '00',
                                    'num_serie' => $data['selSerieCP'],
                                    'num_cpe' => $data['txtNumeroCP'],   
                                    'num_item' => $key1,
                                    'cod_rubro' => $key2,
                                    'cod_usumod' => $this->codUser
                                );
                                if(is_numeric($rub)){
                                    $item['mto_rubro'] = (float)$rub;
                                }else{
                                    $item['des_rubro'] = $rub;
                                }
                                $this->db->insert('cont_cpedata',$item);
                            }      
                        }
                        $json['cod'] = 200;
                        $json['msg'] = 'Ok';
                        $json['res']['lstNotas'] = $this->listaNotas($data['per']);
                    }else{
                        $json['cod'] = 201;
                        $json['msg'] = 'No se actualizaron datos';  
                    }
                }else{
                    $json['cod'] = 204;
                    $json['msg'] = 'El comprobante no puede ser modificado, por que ya fue informado';  
                }
            }

        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        return $json;
    }
    private function eliminarComprobante($data,$json){
        try {
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('cod_cpe','00');
            $this->db->where('num_serie',$data['selSerieCP']);
            $this->db->where('num_cpe',$data['txtNumeroCP']);
            $queryCp = $this->db->get('cont_cpe');
            if(!$queryCp->num_rows()>0){
                $json['cod'] = 204;
                $json['msg'] = 'El documento no existe';
            }else{
                $cpedb = [];
                foreach( $queryCp->result_array() as $row ){ $cpedb[] = $row;}
                if($cpedb[0]['ind_informado'] == 0){
                    $where = array(   
                        'num_ruc' => $this->rucEmpr,
                        'cod_cpe' => '00',
                        'num_serie' => $data['selSerieCP'],
                        'num_cpe' => $data['txtNumeroCP']
                    ); 
                    $res = $this->db->delete('cont_cpedata',$where);
                    if($res){ 
                        $rescp = $this->db->delete('cont_cpe',$where);
                        if($rescp){ 
                            $json['cod'] = 200;
                            $json['msg'] = 'Ok';
                            $json['res']['lstNotas'] = $this->listaNotas($data['per']);
                        }else{
                            $json['cod'] = 201;
                            $json['msg'] = 'No se elimino el comprobante';
                        }
                    }else{
                        $json['cod'] = 201;
                        $json['msg'] = 'No se elimino el comprobante';
                    }
                }else{
                    $json['cod'] = 204;
                    $json['msg'] = 'El comprobante no puede ser eliminado, por que ya fue informado';  
                }
            }
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
        }
        return $json;
    }
    private function listaNotas($per='default') {   
        $this->db->select('*');
        $this->db->from('cont_cpe');
        $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la empresa xxx
        $this->db->where('cod_cpe','00'); //Tipo comprobante 00 - Nota de pedido
        date_default_timezone_set('America/Lima');
        if($per == 'default'){
            $hoy = date('Y-m-d H:i:s');
            $firstDay = date("Y-m-01", strtotime($hoy));
            $lastDay = date("Y-m-t", strtotime($hoy));
            $this->db->where('fec_emision >=',$firstDay);
            $this->db->where('fec_emision <=',$lastDay);
        }else{
            $ano = substr($per,0,4);
            $mes = substr($per,4,2);
            $firstDay = date("Y-m-01", strtotime($ano.'-'.$mes.'-01 00:00:00'));
            $lastDay = date("Y-m-t", strtotime($ano.'-'.$mes.'-01 00:00:00'));
            $this->db->where('fec_emision >=',$firstDay);
            $this->db->where('fec_emision <=',$lastDay);
        }
        //$this->db->order_by('fec_emision', 'DESC'); 
        $array = array();     
        $query = $this->db->get();   
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
                $lstDetalle = array();
                foreach( $qry->result_array() as $res ){
                    $items[] = $res;
                }
                if(count($items)>0){
                    foreach( $items as $line ) {
                        //die($line['num_item']);
                        if($line['num_item']==0) {
                            $lstDetalle[][] = array(
                                'rub' => trim($line['cod_rubro']),
                                'val' => trim($line['mto_rubro']),
                                'des' => trim($line['des_rubro'])
                            );
                        }else{
                            $lstItem[][] = array(
                                'rub' => trim($line['cod_rubro']),
                                'val' => trim($line['mto_rubro']),
                                'des' => trim($line['des_rubro'])
                            );                            
                        }
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
                    'det' => $lstDetalle,
                    'itm' => $lstItem
                );   
                $array[] = $docs;
            }
        }        
        return $array;
    }
    private function listaSeries() {
        $this->db->select('*');
        $this->db->from('cont_cpeserie');
        $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la empresa xxx
        $this->db->where('cod_cpe','00'); //Tipo comprobante 00 - Nota de pedido
        $this->db->where('ind_estado','1'); //Tipo comprobante 00 - Nota de pedido
        $this->db->order_by('num_serie', 'ASC');   
        $query = $this->db->get();   
        $data = [];
        foreach( $query->result_array() as $reg ){
            $data[] = $reg;
        }
        $array = array();
        if(count($data)>0){
            foreach( $data as $row ){
                $docs = array(
                    'ser' => trim($row['num_serie'])
                );   
                $array[] = $docs;
            }
        }        
        return $array;        
    }
    private function listaProductoServ() {   
        $this->db->select('*');
        $this->db->from('alma_prodserv');
        $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la empresa xxx
        $this->db->where('cod_estado','01'); //Tipo comprobante 00 - Nota de pedido
        $this->db->order_by('cod_producto', 'ASC');  
        //$this->db->limit(100); 
        $query = $this->db->get();   
        $data = [];
        foreach( $query->result_array() as $reg ){
            $data[] = $reg;
        }
        //die(print_r($data));
        $array = array();
        if(count($data)>0){
            foreach( $data as $row ){
                $docs = array(
                    'cod' => trim($row['cod_producto']),
                    'des' => trim($row['des_producto']),
                    'lar' => trim($row['des_larga']),
                    'pun' => trim($row['mto_precio']),
                    'est' => trim($row['cod_estado'])
                );   
                $array[] = $docs;
            }
        }        
        return $array;
    }
    public function generarPdfComprobante($data,$json) {
        try {
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('cod_cpe','00');
            $this->db->where('num_serie',$data['selSerieCP']);
            $this->db->where('num_cpe',$data['txtNumeroCP']);
            $queryCp = $this->db->get('cont_cpe');
            if(!$queryCp->num_rows()>0){
                $json['cod'] = 204;
                $json['msg'] = 'El documento no existe';
                echo json_encode($json);
            }else{
                $cpePdf = [];
                $list = [];
                foreach( $queryCp->result_array() as $row ){ $cpePdf[] = $row;} 
                $dataCpe = $cpePdf[0];
                $this->db->where('num_ruc',$this->rucEmpr);
                $this->db->where('cod_cpe','00');
                $this->db->where('num_serie',$data['selSerieCP']);
                $this->db->where('num_cpe',$data['txtNumeroCP']);
                $this->db->order_by('num_item', 'ASC');
                $this->db->order_by('cod_rubro', 'ASC');
                $qry = $this->db->get('cont_cpedata');
                foreach( $qry->result_array() as $row ){
                    if($row['cod_rubro']=='01') $list[$row['num_item']]['dir']= $row['des_rubro'];
                    if($row['cod_rubro']=='02') $list[$row['num_item']]['obs']= $row['des_rubro']; 
                    if($row['cod_rubro']=='81') $list[$row['num_item']]['cnt']= $row['mto_rubro'];
                    if($row['cod_rubro']=='83') $list[$row['num_item']]['cod']= $row['des_rubro'];
                    if($row['cod_rubro']=='84') $list[$row['num_item']]['des']= $row['des_rubro'];
                    if($row['cod_rubro']=='85') $list[$row['num_item']]['pun']= $row['mto_rubro'];
                    if($row['cod_rubro']=='99') $list[$row['num_item']]['imp']= $row['mto_rubro'];
                }
                $alto_pagina = count($list)*4;
                $this->load->library('fpdf/FPDF');
                $pdf = new FPDF("P","mm",array(58,95+$alto_pagina));
                $pdf->SetMargins(-1,0,0);
                $pdf->SetAutoPageBreak(true,5);
                $pdf->AddPage();
                $pdf->SetFont('Times','',10);
                //$pdf->Cell(58,5,'DWWDA',1);
                $pdf->Ln(5);
                $pdf->MultiCell(58,4,$this->desEmpr,0,'C');
                $pdf->SetFontSize(9);
                //$pdf->Cell(58,4,'RUC: 20601344557',0,0,'C');
                //$pdf->Cell(58,4,$this->rucEmpr,0,0,'C');
                //$pdf->Ln();
                $pdf->MultiCell(58,4,$this->dirEmpr,0,'C');
                $pdf->Cell(58,4,'Tlf: '.$this->tlfEmpr,0,0,'C');
                $pdf->SetFontSize(10);
                $pdf->Ln(8);
                $pdf->Cell(58,4,'NOTA DE PEDIDO '.$dataCpe['num_serie'].'-'.$dataCpe['num_cpe'],0,0,'C');
                $pdf->Ln(8);
                $pdf->Cell(58,4,'Fecha de Emision: '.$dataCpe['fec_emision'],0,0,'L');
                $pdf->Ln();
                $pdf->Cell(58,4,'Vendedor : '.$this->desUser,0,0,'L');
                $pdf->Ln();
                $pdf->Cell(58,4,'Documento : '.$dataCpe['num_docrecep'],0,0,'L');
                $pdf->Ln();
                $pdf->Cell(58,4,'Cliente : '.$dataCpe['des_nomrecep'],0,0,'L');
                $pdf->Ln();
                $pdf->Cell(58,4,'Direccion : '.$list[0]['dir'],0,0,'L');

                $pdf->Ln(5);
                $pdf->Cell(7,4,'Cod','TB',0,'L');
                $pdf->Cell(22,4,'Descripcion','TB',0,'L');
                $pdf->Cell(8,4,'Cant','TB',0,'C');
                $pdf->Cell(10.5,4,'P.Unit','TB',0,'C');
                $pdf->Cell(12,4,'Importe','TB',0,'C');

                foreach($list as $key=>$itm){
                    if($key != 0){
                        $pdf->Ln();
                        $pdf->Cell(7,4,substr($itm['cod'],0,4),0,0,'L');
                        $pdf->Cell(22,4,substr($itm['des'],0,12),0,0,'L');
                        $pdf->Cell(8,4,(float)$itm['cnt'],0,0,'R');
                        $pdf->Cell(10.5,4,$itm['pun'],0,0,'R');
                        $pdf->Cell(12.5,4,$itm['imp'],0,0,'R');
                    }
                }
                /*
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
                */
                $pdf->Ln();
                $pdf->Cell(47.5,4,'TOTAL',1,0,'R');
                $pdf->Cell(12.5,4,$dataCpe['mto_imptotal'],1,0,'R');
                
                $pdf->Ln(10);
                $pdf->Cell(58,4,'Gracias por su compra!',0,0,'C');
                $pdf->Ln(10);
                $pdf->SetFontSize(6);
                $pdf->Cell(58,4,'NC v1.0         usuario:'.$this->desUser.'       2021 R',0,0,'R');
                $pdf->Ln();
                $pdf->SetFontSize(18);
                $pdf->Cell(58,4,'-',0,0,'L');
                $pdf->Output();  
            }   
        } 
        catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Excepción capturada: '.$e->getMessage()."\n";
            echo json_encode($json);
        }
        
    }
    
}
