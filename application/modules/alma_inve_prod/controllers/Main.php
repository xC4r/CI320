<?php
if (!defined('BASEPATH')){exit('No direct script access allowed');}

ini_set('memory_limit', '-1');

class Main extends MX_Controller {
    private $codUser;
    private $desUser;
    private $errorInterno = 'Error Interno';
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

    public function defaultLoad(){
        $json = array('cod' => '','msg' => '','res' => array());

            try {
                $json['cod'] = 200;
                $json['msg'] = "Ok";
                $json['res']['lstProductosInve'] = $this->listaProductosInve();
                $json['res']['lstProductos'] = $this->listaProductos();
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = $this->errorInterno;
            }

        echo json_encode($json);
    }

    public function listarProductosInve($inv='default'){
        $json = array('cod' => '','msg' => '','res' => array());
        $inv = $this->input->get('inv', TRUE); 
                try {
                    $json['res']['lstProductosInve'] = $this->listaProductosInve($inv);
                    $json['cod'] = 200;
                    $json['msg'] = "Ok";
                } catch (Exception $e) {
                    $json['cod'] = 204;
                    $json['msg'] = $this->errorInterno;
                }   
        echo json_encode($json);       
    }

    public function registrarMovimientoManual(){
        $json = array('cod' => '','msg' => '','res' => array());
        $dataArray = $this->input->post(NULL, TRUE);
        try {
            if(trim($dataArray['mov'])!==''){

                $this->db->select_max('num_movimiento');
                $this->db->where('num_ruc',$this->rucEmpr);
                $this->db->where('num_inventario',1);
                $this->db->where('cod_producto',$dataArray['txtRegMovCodigo']);
                $queryMax = $this->db->get('alma_movinve');
                $numMaxMovInve = 0;
                $result = $queryMax->result_array();
                if($result[0]['num_movimiento'] !== null){
                    $numMaxMovInve =  $result[0]['num_movimiento']+1;
                }else{
                    $numMaxMovInve = 1;
                }
                $this->db->where('num_ruc', $this->rucEmpr);
                $this->db->where('num_inventario', 1);
                $this->db->where('cod_producto', $dataArray['txtRegMovCodigo']);
                $inventario = $this->db->get('alma_inventario');
                $dataInve = [];
                foreach ($inventario->result_array() as $reg) {
                    $dataInve[] = $reg;
                }
                $cntMovimiento = 0;
                if($dataArray['mov']=='add'){
                    $cntMovimiento = (float)$dataArray['txtRegMovStock'];
                }else if($dataArray['mov']=='des'){
                    $cntMovimiento = -1*(float)$dataArray['txtRegMovStock'];
                }

                $updateInventario = [
                    'num_ruc' => $this->rucEmpr,
                    'num_inventario' => 1,
                    'cod_producto' => $dataArray['txtRegMovCodigo'],                
                    'num_movimiento' => $numMaxMovInve,
                    'ind_tipmovim' => '1',
                    'cnt_movimiento' => $cntMovimiento,
                    'cnt_stockprev' => (float) $dataInve[0]['cnt_stock'],
                    'cnt_stockpost' => (float) $dataInve[0]['cnt_stock'] + $cntMovimiento,
                    'des_motivo' => $dataArray['txtRegMovMotivo']
                ];
                
                $updateInv = array(
                    'cnt_stock' => (float) $dataInve[0]['cnt_stock'] + $cntMovimiento
                );
                $whereInv = array(   
                    'num_ruc' => $this->rucEmpr,
                    'num_inventario' => 1,
                    'cod_producto' => $dataArray['txtRegMovCodigo'], 
                );
                $this->db->update('alma_inventario', $updateInv, $whereInv);

                $movimiento = [
                    'num_ruc' => $this->rucEmpr,
                    'num_inventario' => 1,
                    'cod_producto' => $dataArray['txtRegMovCodigo'],                
                    'num_movimiento' => $numMaxMovInve,
                    'ind_tipmovim' => '1',
                    'cnt_movimiento' => $cntMovimiento,
                    'cnt_stockprev' => (float) $dataInve[0]['cnt_stock'],
                    'cnt_stockpost' => (float) $dataInve[0]['cnt_stock'] + $cntMovimiento,
                    'des_motivo' => $dataArray['txtRegMovMotivo']
                ];
                $this->db->insert('alma_movinve', $movimiento);

                $json['res']['lstProductosInve'] = $this->listaProductosInve(1);
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            }else{
                $json['cod'] = 204;
                $json['msg'] = 'Movimiento incorrecto';
            }
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = $this->errorInterno;
        }
        echo json_encode($json);       
    }

    public function listarMovimientosInve(){
        $json = array('cod' => '','msg' => '','res' => array());
        $prod = $this->input->get('prod', TRUE); 
        try {
            $json['res']['lstMovimientosInve'] = $this->listaMovimientosInve($prod,1);
            $json['cod'] = 200;
            $json['msg'] = "Ok";
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = $this->errorInterno;
        }
        echo json_encode($json);       
    }

    public function operacion()
    {
        $json = array('cod' => '', 'msg' => '', 'res' => array());
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
    
        if (count(array_diff_key($requestPrototype, $post)) > 0) {
            $json['cod'] = 201;
            $json['msg'] = 'Request Error';
            echo json_encode($json);
            return;
        }
    
        if (empty($post['codUsuario']) || empty($post['docUsuario']) || empty($post['formulario'])) {
            $json['cod'] = 201;
            $json['msg'] = 'Error operacion invalida';
            echo json_encode($json);
            return;
        }
    
        try {
            $dataArray = json_decode(str_replace('"[', '[', str_replace(']"', ']', str_replace('\\', '', $post['formulario']))), true);
    
            if (isset($dataArray['ope'])) {
                switch ($dataArray['ope']) {
                    case 1:
                        $json = $this->registrarProductoInve($dataArray, $json);
                        break;
                    case 2:
                        $json = $this->actualizarProductoInve($dataArray, $json);
                        break;
                    case '0':
                        $json = $this->eliminarProductoInve($dataArray, $json);
                        break;
                    default:
                        $json['cod'] = 204;
                        $json['msg'] = 'Error datos invalidos';
                }
            } else {
                $json['cod'] = 204;
                $json['msg'] = 'Error datos invalidos';
            }
        } catch (Exception $e) {
            $json['cod'] = 203;
            $json['msg'] = $this->errorInterno;
        }
    
        echo json_encode($json);
    }

    private function registrarProductoInve($data,$json){
        try {
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('cod_producto',$data['txtRegCodigo']);
            $queryProd = $this->db->get('alma_producto');

            if($queryProd->num_rows()>0){
                $json['cod'] = 204;
                $json['msg'] = 'El codigo de producto ya existe en el catalogo';
            }else {
                $insertProd = array(
                    'num_ruc' => $this->rucEmpr,
                    'cod_producto' => $data['txtRegCodigo'],
                    'des_producto' => $data['txtRegDescProd'],
                    'cod_unimed' => $data['selRegUnidad'], 
                    'mto_precio' => $data['txtRegPcompra'],
                    'cod_estado' => $data['selRegEstado']
                );
                $resProd = $this->db->insert('alma_producto',$insertProd);
                if($resProd){
                    $this->db->where('num_ruc',$this->rucEmpr);
                    $this->db->where('num_inventario',$data['numInv']);
                    $this->db->where('cod_producto',$data['txtRegCodigo']);
                    $queryCp = $this->db->get('alma_inventario');

                    if($queryCp->num_rows()>0){
                        $json['cod'] = 204;
                        $json['msg'] = 'El producto ya existe en el inventario';
                    }else {
                        $insert = array(
                            'num_ruc' => $this->rucEmpr,
                            'num_inventario' => $data['numInv'],
                            'cod_producto' => $data['txtRegCodigo'],
                            'cnt_stock' => $data['txtRegStock'],   
                            'mto_pcompra' => $data['txtRegPcompra'],
                            'mto_pventa' => $data['txtRegPventa'],
                            'cod_estado' => $data['selRegEstado']
                        );
                        $res = $this->db->insert('alma_inventario',$insert);

                        $insertLista = array(
                            'num_ruc' => $this->rucEmpr,
                            'num_inventario' => $data['numInv'],
                            'num_listaprod' => 1,
                            'cod_producto' => $data['txtRegCodigo'],
                            'cod_estado' => $data['selRegEstado']
                        );
                        $resLista = $this->db->insert('alma_listaproducto',$insertLista);

                        if($res && $resLista){
                            $json['cod'] = 200;
                            $json['msg'] = 'Ok';
                            $json['res']['lstProductosInve'] = $this->listaProductosInve($data['numInv']);
                        }else{
                            $json['cod'] = 201;
                            $json['msg'] = 'No se registraron los datos';  
                        }


                    }
                }
            }
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = $this->errorInterno;
        }
        return $json;
    }
    private function actualizarProductoInve($data,$json){
        try {
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('num_inventario',$data['numInv']);
            $this->db->where('cod_producto',$data['codProd']);
            $queryCp = $this->db->get('alma_inventario');

            if($queryCp->num_rows()<1){
                $json['cod'] = 204;
                $json['msg'] = 'El producto no existe en el inventario';
            }else if($queryCp->num_rows()>1){
                $json['cod'] = 204;
                $json['msg'] = 'El producto esta repetido';                 
            }else if($queryCp->num_rows()==1){
                $updateProd = array(
                    'cod_unimed' => $data['selRegUnidad'],
                    'cod_estado' => $data['selRegEstado'],
                    'des_producto' => $data['txtRegDescProd']
                );
                $whereProd = array(   
                    'num_ruc' => $this->rucEmpr,
                    'cod_producto' => $data['codProd']
                );
                $resProd = $this->db->update('alma_producto', $updateProd, $whereProd);

                $update = array(
                    'cnt_stock' => $data['txtRegStock'],   
                    'mto_pcompra' => $data['txtRegPcompra'],
                    'mto_pventa' => $data['txtRegPventa'],
                    'cod_estado' => $data['selRegEstado']
                );
                $where = array(   
                    'num_ruc' => $this->rucEmpr,
                    'num_inventario' => $data['numInv'],
                    'cod_producto' => $data['codProd']
                );
                $res = $this->db->update('alma_inventario', $update, $where);

                $updateLista = array(
                    'cod_estado' => $data['selRegEstado']
                );
                $whereLista = array(   
                    'num_ruc' => $this->rucEmpr,
                    'num_inventario' => $data['numInv'],
                    'num_listaprod' => 1,
                    'cod_producto' => $data['codProd']
                );
                $resLista = $this->db->update('alma_listaproducto', $updateLista, $whereLista);

                if($res && $resLista && $resProd){
                    $json['cod'] = 200;
                    $json['msg'] = 'Ok';
                    $json['res']['lstProductosInve'] = $this->listaProductosInve($data['numInv']);
                }else{
                    $json['cod'] = 201;
                    $json['msg'] = 'No se actualizaron los datos';  
                }
            }    
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = $this->errorInterno;
        }
        return $json;
    }
    private function eliminarProductoInve($data,$json){
        try {
            $this->db->where('num_ruc',$this->rucEmpr);
            $this->db->where('num_inventario',$data['numInv']);
            $this->db->where('cod_producto',$data['codProd']);
            $querylst = $this->db->get('alma_listaproducto');

            if(!$querylst->num_rows()>0){
                $this->db->where('num_ruc',$this->rucEmpr);
                $this->db->where('num_inventario',$data['numInv']);
                $this->db->where('cod_producto',$data['codProd']);
                $queryInv = $this->db->get('alma_inventario');

                if($queryInv->num_rows()<1){
                    $json['cod'] = 204;
                    $json['msg'] = 'El documento no existe';
                }else{
                    $where = array(   
                        'num_ruc' => $this->rucEmpr,
                        'num_inventario' => $data['numInv'],
                        'cod_producto' => $data['codProd']
                    );
                    $res = $this->db->delete('alma_inventario',$where);
                    if($res){ 
                        $json['cod'] = 200;
                        $json['msg'] = 'Ok';
                        $json['res']['lstProductosInve'] = $this->listaProductosInve($data['numInv']);
                    }else{
                        $json['cod'] = 201;
                        $json['msg'] = 'No se eliminaron los datos';
                    }
                }
            }else{
                $json['cod'] = 201;
                $json['msg'] = 'No se puede eliminar el producto, por que se encuentra en una lista';
            }
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = $this->errorInterno;
        }
        return $json;
    }
    private function listaProductosInve($inv='default') {   
        $this->db->select('alma_inventario.num_inventario,alma_inventario.cod_producto,,alma_inventario.cnt_stock,alma_inventario.mto_pcompra,alma_inventario.mto_pventa,alma_inventario.cod_estado,
        alma_producto.des_producto,alma_producto.cod_unimed');
        $this->db->from('alma_inventario');
        $this->db->join('alma_producto', 'alma_inventario.cod_producto = alma_producto.cod_producto');
        $this->db->where('alma_inventario.num_ruc',$this->rucEmpr); 
        if($inv=='default'){
            $this->db->where('alma_inventario.num_inventario',1);
        }else{
            $this->db->where('alma_inventario.num_inventario',$inv);   
        }
        $array = array();     
        $query = $this->db->get();   
        $data = [];
        foreach( $query->result_array() as $reg ){
            $data[] = $reg;
        }

        if(count($data)>0){
            foreach( $data as $row ) {
                $des_estado = '';
                if($row['cod_estado']=='01'){
                    $des_estado = 'Disponible';
                } else if ($row['cod_estado']=='02'){
                    $des_estado = 'No Disponible';
                }

                $docs = array(
                    'inv' => trim($row['num_inventario']),
                    'cod' => trim($row['cod_producto']),
                    'des' => trim($row['des_producto']),
                    'stk' => $row['cnt_stock'],
                    'pco' => $row['mto_pcompra'],
                    'pvt' => $row['mto_pventa'],
                    'ces' => $row['cod_estado'],
                    'est' => $des_estado,
                    'und' => $row['cod_unimed']
                );   
                $array[] = $docs;
            }
        }        
        return $array;
    }

    private function listaMovimientosInve($codProducto, $numInventario)
    {
        $this->db->where('num_ruc', $this->rucEmpr);
        $this->db->where('num_inventario', $numInventario);
        $this->db->where('cod_producto', $codProducto);
        $this->db->order_by('num_movimiento', 'DESC');
        $query = $this->db->get('alma_movinve');
        $array = array();
    
        foreach ($query->result_array() as $row) {
            $des_operacion = '';
            if ($row['cod_operacion'] == '01') {
                $des_operacion = 'Registro';
            } else if ($row['cod_operacion'] == '02') {
                $des_operacion = 'Modificacion';
            } else if ($row['cod_operacion'] == '00') {
                $des_operacion = 'Eliminacion';
            }
    
            $nomCliente = '';
            $cpe = '';
    
            if ($row['ind_tipmovim'] == '0') {
                $this->db->where('num_ruc', $this->rucEmpr);
                $this->db->where('cod_cpe', '00');
                $this->db->where('num_serie', $row['num_serie']);
                $this->db->where('num_cpe', $row['num_cpe']);
                $queryCli = $this->db->get('cont_cpe');
                $cliente = $queryCli->row_array();
    
                if ($cliente) {
                    $nomCliente = $cliente['des_nomrecep'];
                    $cpe = 'Nota ' . $row['num_serie'] . '-' . $row['num_cpe'];
                } else {
                    $nomCliente = 'CPE Eliminado';
                    $cpe = 'Nota ' . $row['num_serie'] . '-' . $row['num_cpe'];
                }
            }
    
            $docs = array(
                'num' => $row['num_movimiento'],
                'fec' => $row['fec_movimiento'],
                'cnt' => $row['cnt_movimiento'],
                'stk' => $row['cnt_stockprev'],
                'stp' => $row['cnt_stockpost'],
                'mot' => $row['des_motivo'],
                'tip' => $des_operacion,
                'cpe' => $cpe,
                'cli' => $nomCliente
            );
    
            $array[] = $docs;
        }
    
        return $array;
    }
    
    private function listaProductos() {   
        $this->db->select('*');
        $this->db->from('alma_producto');
        $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la empresa xxx
        $this->db->where('cod_estado','01'); //Tipo comprobante 00 - Nota de pedido
        $this->db->order_by('cod_producto', 'ASC');  
        $query = $this->db->get();   
        $data = [];
        foreach( $query->result_array() as $reg ){
            $data[] = $reg;
        }
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
    
}
