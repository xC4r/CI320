<?php
if (!defined('BASEPATH')){exit('No direct script access allowed');}

ini_set('memory_limit', '-1');


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

    public function defaultLoad(){
        $json = array('cod' => '','msg' => '','res' => array());
        $headers = $this->input->request_headers();
        $this->load->library('Auth');  

            try {
                $json['res']['lstNotas'] = $this->listaNotas();
                $json['res']['lstSeries'] = $this->listaSeries();
                $json['res']['lstProdserv'] = $this->listaProductos();
                $json['cod'] = 200;
                $json['msg'] = "Ok";
            } catch (Exception $e) {
                $json['cod'] = 204;
                $json['msg'] = 'Error:'.$e->getMessage().'\n';
            }

        echo json_encode($json);
    }
    public function listarNotasPedido(){
        $json = array('cod' => '','msg' => '','res' => array());
        $per = $this->input->get('per', TRUE); 
        $headers = $this->input->request_headers();
        $this->load->library('Auth');  

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
     
        echo json_encode($json);       
    }

    public function operacion() {
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
                        $json = $this->registrarComprobante($dataArray, $json);
                        break;
                    case 2:
                        $json = $this->actualizarComprobante($dataArray, $json);
                        break;
                    case '0':
                        $json = $this->eliminarComprobante($dataArray, $json);
                        break;
                    case 'pdf':
                        $this->generarPdfComprobante($dataArray, $json);
                        return;
                    default:
                        $json['cod'] = 204;
                        $json['msg'] = 'Error datos invalidos';
                        break;
                }
            } else {
                $json['cod'] = 204;
                $json['msg'] = 'Error datos invalidos';
            }
        } catch (Exception $e) {
            $json['cod'] = 203;
            $json['msg'] = 'Error Interno';
        }
        echo json_encode($json);
    }
    
    public function obtenerNumCp() {   
        $json = array('cod' => '','msg' => '','res' => array());
        $codCpe = '00';
        $numSerie = $this->input->get('numSerie', TRUE);
        $json['cod'] = 200;
        $json['msg'] = 'Ok';
        $json['res']['numCp'] = $this->obtenerCorrelativo($codCpe,$numSerie);
       
        echo json_encode($json); 
    }

    private function obtenerCorrelativo($codCpe,$numSerie) {   
        $this->db->select_max('num_cpe');
        $this->db->from('cont_cpe');
        $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la empresa xxx
        $this->db->where('cod_cpe',$codCpe); //Tipo comprobante 00 - Nota de pedido
        $this->db->where('num_serie',$numSerie);
        $query = $this->db->get();   
        $data = [];
        foreach( $query->result_array() as $reg ){
            $data[] = $reg;
        }
        if(count($data)>0){
            return (int) $data[0]['num_cpe'] + 1;
        } else{
            return 1;
        }       
    }

    public function validarNumCp() {
        $json = array('cod' => '','msg' => '','res' => array());
        $numSerie = $this->input->get('numSerie', TRUE); 
        $numCp = (int) $this->input->get('numCp', TRUE); 
        $headers = $this->input->request_headers();
        $this->load->library('Auth');  

            if(is_int($numCp)&&$numCp>0 && isset($numSerie)){
                $this->db->select('*');
                $this->db->from('cont_cpe');
                $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la emppresa
                $this->db->where('cod_cpe','00'); //Tipo comprobante 00 - Nota de pedido
                $this->db->where('num_serie',$numSerie); //numero de serie
                $this->db->where('num_cpe',(int) $numCp); //Tipo comprobante 00 - Nota
                $query = $this->db->get();   
                $data = [];
                foreach( $query->result_array() as $reg ){
                    $data[] = $reg;
                }
                if(count($data)>0){
                    $json['cod'] = 201;
                    $json['msg'] = 'El numero de comprobante ya existe';
                    $json['res']['numCp'] = $this->obtenerCorrelativo('00',$numSerie);
                } else{
                    $json['cod'] = 200;
                    $json['msg'] = 'Ok';
                }
            }else{
                $json['cod'] = 201;
                $json['msg'] = 'Datos incorrectos';
                $json['res']['numCp'] = $this->obtenerCorrelativo('00',$numSerie);
            }

        echo json_encode($json); 
    }
    
private function validardStockDisponible($numInventario, $listItems, $operacion, $data)
{
    $validacion = array('ind' => 0, 'items' => array());
    $lst_items_sin_stock = array();

    foreach ($listItems as $row) {
        $indStockDisponible = 1;
        if (trim($row['cod']) !== '') {
            $this->db->where('num_ruc', $this->rucEmpr);
            $this->db->where('num_inventario', $numInventario);
            $this->db->where('cod_producto', $row['cod']);
            $inventario = $this->db->get('alma_inventario');
            $dataInve = $inventario->result_array();

            if ($operacion == 1 || $operacion === 2) {
                $item_stock = $this->getItemStockData($row, $dataInve, $indStockDisponible, $operacion);
                $lst_items_sin_stock[] = $item_stock;
            }
            


        }
    }

    if ($operacion == 0) {
        $item_stock = $this->getCancelStockData($numInventario, $data);
        $lst_items_sin_stock[] = $item_stock;
    }

    $validacion['items'] = $lst_items_sin_stock;
    return $validacion;
}

private function getItemStockData($row, $dataInve, &$indStockDisponible, $operacion)
{
    $item_stock = [];
    if (count($dataInve) > 0) {
        $cnt_orig_prod = 0;

        if ($operacion === 2) {
            $cnt_orig_prod = $this->getOriginalProductCount($row, $data);
        }

        if ($row['ind'] == '1') {
            if ((float)$dataInve[0]['cnt_stock'] < (0 - $cnt_orig_prod)) {
                $indStockDisponible = 0;
            }
            $item_stock = array(
                'cod' => trim($row['cod']),
                'des' => trim($row['des']),
                'cnt' => (float)$row['cnt'],
                'stk' => (float)$dataInve[0]['cnt_stock'],
                'cntDescontar' => -$cnt_orig_prod,
                'indStockDisponible' => $indStockDisponible
            );
        } else {
            if ((float)$dataInve[0]['cnt_stock'] < ((float)$row['cnt'] - $cnt_orig_prod)) {
                $indStockDisponible = 0;
            }
            $item_stock = array(
                'cod' => trim($row['cod']),
                'des' => trim($row['des']),
                'cnt' => (float)$row['cnt'],
                'stk' => (float)$dataInve[0]['cnt_stock'],
                'cntDescontar' => (float)$row['cnt'] - $cnt_orig_prod,
                'indStockDisponible' => $indStockDisponible
            );
        }
    }

    return $item_stock;
}

private function getOriginalProductCount($row, $data)
{
    $cnt_orig_prod = 0;

    $this->db->where('num_ruc', $this->rucEmpr);
    $this->db->where('cod_cpe', '00');
    $this->db->where('num_serie', $data['selSerieCP']);
    $this->db->where('num_cpe', $data['txtNumeroCP']);
    $this->db->where('cod_rubro', '81');
    $queryitm = $this->db->get('cont_cpedata');
    $itmCod = $queryitm->result_array();

    if (count($itmCod) > 0) {
        $cnt_orig_prod = (float)$itmCod[0]['mto_rubro'];
    }

    return $cnt_orig_prod;
}

private function getCancelStockData($numInventario, $data)
{
    $item_stock = [];
    $cnt_orig_prod = 0;

    $this->db->select('*');
    $this->db->from('cont_cpedata');
    $this->db->where('num_ruc', $this->rucEmpr);
    $this->db->where('cod_cpe', '00');
    $this->db->where('num_serie', $data['selSerieCP']);
    $this->db->where('num_cpe', $data['txtNumeroCP']);
    $this->db->order_by('num_item', 'ASC');
    $this->db->order_by('cod_rubro', 'ASC');
    $qry = $this->db->get();
    $items = $qry->result_array();
    $lstItem = array();

    foreach ($items as $line) {
        if ($line['num_item'] !== 0) {
            if ($line['cod_rubro'] == 81) {
                $lstItem[$i]['cnt'] = $line['mto_rubro'];
            }
            if ($line['cod_rubro'] == 82) {
                $lstItem[$i]['und'] = $line['des_rubro'];
            } else {
                $lstItem[$i]['und'] = '';
            }
            if ($line['cod_rubro'] == 83) {
                $lstItem[$i]['cod'] = $line['des_rubro'];
            }
            if ($line['cod_rubro'] == 84) {
                $lstItem[$i]['des'] = $line['des_rubro'];
            }
            if ($line['cod_rubro'] == 85) {
                $lstItem[$i]['pun'] = $line['mto_rubro'];
            }
            if ($line['cod_rubro'] == 99) {
                $lstItem[$i]['imp'] = $line['mto_rubro'];
            }
        }
    }

    if (count($lstItem) > 0) {
        foreach ($lstItem as $row) {
            $this->db->where('num_ruc', $this->rucEmpr);
            $this->db->where('num_inventario', $numInventario);
            $this->db->where('cod_producto', $row['cod']);
            $inventario = $this->db->get('alma_inventario');
            $dataInve = $inventario->result_array();
            $stock_actual = 0;

            if (count($dataInve) > 0) {
                $stock_actual = (float)$dataInve[0]['cnt_stock'];
            }

            $item_stock = array(
                'cod' => trim($row['cod']),
                'des' => trim($row['des']),
                'cnt' => (float)$row['cnt'],
                'stk' => $stock_actual,
                'cntDescontar' => -(float)$row['cnt'],
                'indStockDisponible' => 1
            );
        }
    }

    return $item_stock;
}

private function validarStockDisponible($numInventario, $listItems, $operacion, $data)
{
    $validacion = array('ind' => 0, 'items' => array());
    $lst_items_sin_stock = array();

    if ($operacion == 1 || $operacion == 2) {
        foreach ($listItems as $row) {
            $indStockDisponible = 1;

            if (trim($row['cod']) !== '') {
                $this->db->where('num_ruc', $this->rucEmpr);
                $this->db->where('num_inventario', $numInventario);
                $this->db->where('cod_producto', $row['cod']);
                $inventario = $this->db->get('alma_inventario');
                $dataInve = $inventario->result_array();

                if ($operacion == 1) {
                    $item_stock = [];
                    if ((float)$dataInve[0]['cnt_stock'] < (float)$row['cnt']) {
                        $indStockDisponible = 0;
                        $validacion['ind'] = 1;
                    }
                    $item_stock = array(
                        'cod' => trim($row['cod']),
                        'des' => trim($row['des']),
                        'cnt' => (float)$row['cnt'],
                        'stk' => (float)$dataInve[0]['cnt_stock'],
                        'cntDescontar' => (float)$row['cnt'],
                        'indStockDisponible' => $indStockDisponible
                    );
                    $lst_items_sin_stock[] = $item_stock;
                }
                if ($operacion === 2) {
                    $item_stock = [];
                    $cnt_orig_prod = 0;

                    $this->db->where('num_ruc', $this->rucEmpr);
                    $this->db->where('cod_cpe', '00');
                    $this->db->where('num_serie', $data['selSerieCP']);
                    $this->db->where('num_cpe', $data['txtNumeroCP']);
                    $this->db->where('cod_rubro', '83');
                    $this->db->where('des_rubro', $row['cod']);
                    $queryCpDeta = $this->db->get('cont_cpedata');
                    $itmCpe = $queryCpDeta->result_array();

                    if (count($itmCpe) > 0) {
                        $this->db->where('num_ruc', $this->rucEmpr);
                        $this->db->where('cod_cpe', '00');
                        $this->db->where('num_serie', $data['selSerieCP']);
                        $this->db->where('num_cpe', $data['txtNumeroCP']);
                        $this->db->where('num_item', $itmCpe[0]['num_item']);
                        $this->db->where('cod_rubro', '81');
                        $queryitm = $this->db->get('cont_cpedata');
                        $itmCod = $queryitm->result_array();
                        $cnt_orig_prod = (float)$itmCod[0]['mto_rubro'];
                    }

                    if (count($dataInve) > 0) {
                        if (isset($row['ind']) && $row['ind'] == '1') {
                            if ((float)$dataInve[0]['cnt_stock'] < (0 - $cnt_orig_prod)) {
                                $indStockDisponible = 0;
                                $validacion['ind'] = 1;
                            }
                            $item_stock = array(
                                'cod' => trim($row['cod']),
                                'des' => trim($row['des']),
                                'cnt' => (float)$row['cnt'],
                                'stk' => (float)$dataInve[0]['cnt_stock'],
                                'cntDescontar' => -$cnt_orig_prod,
                                'indStockDisponible' => $indStockDisponible
                            );
                            $lst_items_sin_stock[] = $item_stock;
                        } else {
                            if ((float)$dataInve[0]['cnt_stock'] < ((float)$row['cnt'] - $cnt_orig_prod)) {
                                $indStockDisponible = 0;
                                $validacion['ind'] = 1;
                            }
                            $item_stock = array(
                                'cod' => trim($row['cod']),
                                'des' => trim($row['des']),
                                'cnt' => (float)$row['cnt'],
                                'stk' => (float)$dataInve[0]['cnt_stock'],
                                'cntDescontar' => (float)$row['cnt'] - $cnt_orig_prod,
                                'indStockDisponible' => $indStockDisponible
                            );
                            $lst_items_sin_stock[] = $item_stock;
                        }
                    }
                }
            }
        }
    }

    if ($operacion == 0) {
        $item_stock = [];
        $cnt_orig_prod = 0;

        $this->db->where('num_ruc', $this->rucEmpr);
        $this->db->where('cod_cpe', '00');
        $this->db->where('num_serie', $data['selSerieCP']);
        $this->db->where('num_cpe', $data['txtNumeroCP']);
        $this->db->order_by('num_item', 'ASC');
        $this->db->order_by('cod_rubro', 'ASC');
        $qry = $this->db->get('cont_cpedata');
        $items = $qry->result_array();
        $lstItem = array();

        foreach ($items as $line) {
            if ($line['num_item'] !== 0) {
                if ($line['cod_rubro'] == 81) {
                    $lstItem[$i]['cnt'] = $line['mto_rubro'];
                }
                if ($line['cod_rubro'] == 82) {
                    $lstItem[$i]['und'] = $line['des_rubro'];
                } else {
                    $lstItem[$i]['und'] = '';
                }
                if ($line['cod_rubro'] == 83) {
                    $lstItem[$i]['cod'] = $line['des_rubro'];
                }
                if ($line['cod_rubro'] == 84) {
                    $lstItem[$i]['des'] = $line['des_rubro'];
                }
                if ($line['cod_rubro'] == 85) {
                    $lstItem[$i]['pun'] = $line['mto_rubro'];
                }
                if ($line['cod_rubro'] == 99) {
                    $lstItem[$i]['imp'] = $line['mto_rubro'];
                }
            }
        }

        if (count($lstItem) > 0) {
            foreach ($lstItem as $row) {
                $this->db->where('num_ruc', $this->rucEmpr);
                $this->db->where('num_inventario', $numInventario);
                $this->db->where('cod_producto', $row['cod']);
                $inventario = $this->db->get('alma_inventario');
                $dataInve = $inventario->result_array();
                $stock_actual = 0;

                if (count($dataInve) > 0) {
                    $stock_actual = (float)$dataInve[0]['cnt_stock'];
                }

                if ((float)$row['cnt'] > $stock_actual) {
                    $indStockDisponible = 0;
                    $validacion['ind'] = 1;
                }

                $item_stock = array(
                    'cod' => trim($row['cod']),
                    'des' => trim($row['des']),
                    'cnt' => (float)$row['cnt'],
                    'stk' => $stock_actual,
                    'cntDescontar' => (float)$row['cnt'],
                    'indStockDisponible' => $indStockDisponible
                );
                $lst_items_sin_stock[] = $item_stock;
            }
        }
    }

    $validacion['items'] = $lst_items_sin_stock;
    return $validacion;
}


    private function validarCpExiste($codCpe,$numSerie,$numCpe) {
        $validacion = array('ind' => 0,'dataCpe' => array(),'dataCpeDeta' => array());

        $this->db->where('num_ruc',$this->rucEmpr);
        $this->db->where('cod_cpe',$codCpe);
        $this->db->where('num_serie',$numSerie);
        $this->db->where('num_cpe',$numCpe);
        $queryCp = $this->db->get('cont_cpe');
        $dataCpe = [];
        foreach( $queryCp->result_array() as $reg ){
            $dataCpe[] = $reg;
        }
        $this->db->where('num_ruc',$this->rucEmpr);
        $this->db->where('cod_cpe',$codCpe);
        $this->db->where('num_serie',$numSerie);
        $this->db->where('num_cpe',$numCpe);
        $queryCpDeta = $this->db->get('cont_cpedata');
        $dataCpeDeta = [];
        foreach( $queryCpDeta->result_array() as $reg ){
            $dataCpeDeta[] = $reg;
        }
        $validacion['dataCpeDeta'] = $dataCpeDeta;

        if(count($dataCpe)>0){
            $validacion['ind'] = 1;
            $validacion['dataCpe'] = $dataCpe;
        }
        if(count($dataCpeDeta)>0){
            $validacion['ind'] = 1;
            $validacion['dataCpeDeta'] = $dataCpeDeta;
        }
        return $validacion;
    }
    
    private function registrarComprobante($data, $json)
    {
        try {
            $validaStock = $this->validardStockDisponible(1, $data['itm'], 1, $data);
            $validaCp = $this->validarCpExiste('00', $data['selSerieCP'], $data['txtNumeroCP']);
    
            if ($validaCp['ind'] == 1) {
                $json['cod'] = 204;
                $json['msg'] = 'El comprobante y detalle ya existe';
            } elseif ($validaStock['ind'] == 1) {
                $json['cod'] = 204;
                $json['msg'] = 'Los items del comprobante no tienen stock disponible';
                $json['res'] = $validaStock['items'];
            } elseif ($validaStock['ind'] == 0 && $validaCp['ind'] == 0) {
                $insert = [
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
                    'mto_imptotal' => $data['txtTotal'],
                ];
    
                $res = $this->db->insert('cont_cpe', $insert);
                
                if ($res) {
                    $this->insertRubro('01', $data['txtDireccion'], $data['selSerieCP'], $data['txtNumeroCP']);
                    $this->insertRubro('02', $data['txtObservacion'], $data['selSerieCP'], $data['txtNumeroCP']);
    
                    foreach ($data['itm'] as $key1 => $row) {
                        if (!isset($row['ind'])) {
                            foreach ($row as $key2 => $value) {
                                $rubro = '';
                                switch ($key2) {
                                    case 'und':
                                        $rubro = '82';
                                        break;
                                    case 'cod':
                                        $rubro = '83';
                                        break;
                                    case 'des':
                                        $rubro = '84';
                                        break;
                                    case 'cnt':
                                        $rubro = '81';
                                        break;
                                    case 'pun':
                                        $rubro = '85';
                                        break;
                                    case 'imp':
                                        $rubro = '99';
                                        break;
                                    default:
                                        break;
                                }
                                $this->insertRubro($rubro, $value, $data['selSerieCP'], $data['txtNumeroCP']);
                            }
                        }
    
                        foreach ($validaStock['items'] as $validRow) {
                            if (trim($row['cod']) == $validRow['cod']) {
                                $this->updateInventario($row['cod'], $validRow['cntDescontar'], $data['selSerieCP'], $data['txtNumeroCP']);
                            }
                        }
                    }
    
                    $json['cod'] = 200;
                    $json['msg'] = 'Ok';
                    $json['res']['lstNotas'] = $this->listaNotas($data['per']);
                } else {
                    $json['cod'] = 201;
                    $json['msg'] = 'No se registraron los items';
                }
            }
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = 'Error Interno';
        }
        return $json;
    }
    
    private function insertRubro($rubro, $value, $selSerieCP, $txtNumeroCP)
    {
        $item = [
            'num_ruc' => $this->rucEmpr,
            'cod_cpe' => '00',
            'num_serie' => $selSerieCP,
            'num_cpe' => $txtNumeroCP,
            'num_item' => 0,
            'cod_rubro' => $rubro,
            'cod_usumod' => $this->codUser,
        ];
    
        if (is_numeric($value)) {
            $item['mto_rubro'] = (float) $value;
        } else {
            $item['des_rubro'] = $value;
        }
    
        $this->db->insert('cont_cpedata', $item);
    }
    
    private function updateInventario($cod, $cntDescontar, $selSerieCP, $txtNumeroCP)
    {
        $this->db->where('num_ruc', $this->rucEmpr);
        $this->db->where('num_inventario', 1);
        $this->db->where('cod_producto', $cod);
        $inventario = $this->db->get('alma_inventario');
        $dataInve = $inventario->result_array();
    
        if (abs($cntDescontar) > 0 && count($dataInve) > 0) {
            $update = [
                'cnt_stock' => (float) $dataInve[0]['cnt_stock'] - $cntDescontar,
            ];
    
            $where = [
                'num_ruc' => $this->rucEmpr,
                'num_inventario' => 1,
                'cod_producto' => $cod,
            ];
    
            $this->db->select_max('num_movimiento');
            $this->db->where('num_ruc', $this->rucEmpr);
            $this->db->where('num_inventario', 1);
            $this->db->where('cod_producto', $cod);
            $queryMax = $this->db->get('alma_movinve');
            $numMaxMovInve = 0;
            $result = $queryMax->result_array();
    
            if ($result[0]['num_movimiento'] !== null) {
                $numMaxMovInve = $result[0]['num_movimiento'] + 1;
            } else {
                $numMaxMovInve = 1;
            }
    
            $movimiento = [
                'num_ruc' => $this->rucEmpr,
                'num_inventario' => 1,
                'cod_producto' => $cod,
                'num_movimiento' => $numMaxMovInve,
                'cnt_movimiento' => -1 * $cntDescontar,
                'cnt_stockprev' => (float) $dataInve[0]['cnt_stock'],
                'cnt_stockpost' => (float) $dataInve[0]['cnt_stock'] - $cntDescontar,
                'cod_operacion' => '01',
                'cod_cpe' => '00',
                'num_serie' => $selSerieCP,
                'num_cpe' => $txtNumeroCP,
            ];
    
            $this->db->update('alma_inventario', $update, $where);
            $this->db->insert('alma_movinve', $movimiento);
        }
    }
    

    private function actualizarComprobante($data,$json){
        try {
            $validaStock = $this->validardStockDisponible(1,$data['itm'],2,$data);
            $validaCp = $this->validarCpExiste('00',$data['selSerieCP'],$data['txtNumeroCP']);

            if( $validaCp['ind'] == 0){
                $json['cod'] = 204;
                $json['msg'] = 'Actualizar: El documento no existe';  
            } else if ($validaStock['ind']==1){ 
                $json['cod'] = 204;
                $json['msg'] = 'Los items del cmprobante no tiene stock disponible';
                $json['res'] = $validaStock['items'];             
            } else if ( $validaCp['ind']==1 ){

                if($validaCp['dataCpe'][0]['ind_informado'] == 0){
                    $update = array(   
                        'fec_emision' => $data['txtFecha'],
                        'cod_tipdocrec' => 0,
                        'num_docrecep' => $data['txtDocumento'],
                        'des_nomrecep' => $data['txtNombre'],
                        'cod_moneda' => 'PEN',
                        'mto_tipocambio' => 1,
                        'mto_imptotal' => $data['txtTotal'],
                        'cod_usumod' => $this->codUser
                    );
                    $where = array(   
                        'num_ruc' => $this->rucEmpr,
                        'cod_cpe' => '00', // 00: Nota de Pedido
                        'num_serie' => $data['selSerieCP'],
                        'num_cpe' => $data['txtNumeroCP']
                    );
                    $res = $this->db->update('cont_cpe', $update, $where);
                    if($res){
                        $where = array(   
                            'num_ruc' => $this->rucEmpr,
                            'cod_cpe' => '00', // 00: Nota de Pedido
                            'num_serie' => $data['selSerieCP'],
                            'num_cpe' => $data['txtNumeroCP']
                        );
                        $this->db->delete('cont_cpedata',$where);

                        if($data['txtDireccion']!==''){  
                            $item01 = array(
                                'num_ruc' => $this->rucEmpr,
                                'cod_cpe' => '00', // 00: Nota de Pedido
                                'num_serie' => $data['selSerieCP'],
                                'num_cpe' => $data['txtNumeroCP'],   
                                'num_item' => 0,
                                'cod_rubro' => '01',
                                'des_rubro' => $data['txtDireccion'],
                                'cod_usumod' => $this->codUser
                            );
                            $this->db->insert('cont_cpedata',$item01);
                        }
                        if($data['txtObservacion']!==''){    
                            $item02 = array(
                                'num_ruc' => $this->rucEmpr,
                                'cod_cpe' => '00', // Nota de pedido
                                'num_serie' => $data['selSerieCP'],
                                'num_cpe' => $data['txtNumeroCP'],   
                                'num_item' => 0,
                                'cod_rubro' => '02',
                                'des_rubro' => $data['txtObservacion'],
                                'cod_usumod' => $this->codUser
                            );
                            $this->db->insert('cont_cpedata',$item02);
                        }
                        //Agregar mas rubros del comprobante
                        
                        foreach( $data['itm'] as $key1=>$row ) {
                            if(!isset($row['ind'])){
                                foreach( $row as $key2=>$value ) {
                                    $rubro ='';
                                    switch($key2){
                                        case 'und':
                                            $rubro ='82';
                                        break;                                        
                                        case 'cod':
                                            $rubro ='83';
                                        break;
                                        case 'des':
                                            $rubro ='84';
                                        break;
                                        case 'cnt':
                                            $rubro ='81';
                                        break;
                                        case 'pun':
                                            $rubro ='85';
                                        break;
                                        case 'imp':
                                            $rubro ='99';
                                        break;
                                        default:
                                        break;
                                    }
                                    $item = array(
                                        'num_ruc' => $this->rucEmpr,
                                        'cod_cpe' => '00', // Nota de pedido
                                        'num_serie' => $data['selSerieCP'],
                                        'num_cpe' => $data['txtNumeroCP'],   
                                        'num_item' => $key1+1,
                                        'cod_rubro' => $rubro,
                                        'cod_usumod' => $this->codUser
                                    );
                                    if(is_numeric($value)){
                                        $item['mto_rubro'] = (float)$value;
                                    }else{
                                        $item['des_rubro'] = $value;
                                    }
                                    $this->db->insert('cont_cpedata',$item);
                                }
                            }

                            foreach( $validaStock['items'] as $validRow ) {
                                if(count($validRow)>0){ 
                                    // Actualiza cantidades de stock he inventario y registra movmientos
                                    if(trim($row['cod'])==$validRow['cod']){
                                            $this->db->where('num_ruc',$this->rucEmpr);
                                            $this->db->where('num_inventario',1);
                                            $this->db->where('cod_producto',$row['cod']);
                                            $inventario = $this->db->get('alma_inventario');
                                            $dataInve = [];
                                            foreach( $inventario->result_array() as $reg ){
                                                $dataInve[] = $reg;
                                            }

                                            if (abs($validRow['cntDescontar'])>0 && count($dataInve)>0 ) {
                                                $update = array(
                                                    'cnt_stock' => (float)$dataInve[0]['cnt_stock']-$validRow['cntDescontar']
                                                );
                                                $where = array(   
                                                    'num_ruc' => $this->rucEmpr,
                                                    'num_inventario' => 1,
                                                    'cod_producto' => $row['cod']
                                                );

                                                $this->db->select_max('num_movimiento');
                                                $this->db->where('num_ruc',$this->rucEmpr);
                                                $this->db->where('num_inventario',1);
                                                $this->db->where('cod_producto',$row['cod']);
                                                $queryMax = $this->db->get('alma_movinve');
                                                $numMaxMovInve;
                                                $result = $queryMax->result_array();
                                                if($result[0]['num_movimiento'] !== null){
                                                    $numMaxMovInve =  $result[0]['num_movimiento']+1;
                                                }else{
                                                    $numMaxMovInve = 1;
                                                }

                                                $movimiento = array(   
                                                    'num_ruc' => $this->rucEmpr,
                                                    'num_inventario' => 1,
                                                    'cod_producto' => $row['cod'],
                                                    'num_movimiento' => $numMaxMovInve, 
                                                    'cnt_movimiento' => -1*$validRow['cntDescontar'], 
                                                    'cnt_stockprev' => (float)$dataInve[0]['cnt_stock'], 
                                                    'cnt_stockpost' => (float)$dataInve[0]['cnt_stock']-$validRow['cntDescontar'], 
                                                    'cod_operacion' => '02', 
                                                    'cod_cpe' => '00',
                                                    'num_serie' => $data['selSerieCP'],
                                                    'num_cpe' => $data['txtNumeroCP']
                                                );
                                                $this->db->update('alma_inventario', $update, $where);
                                                $this->db->insert('alma_movinve',$movimiento);
                                        }
                                    }

                                }
                            }
                        }
                        
                    }else{
                        $json['cod'] = 201;
                        $json['msg'] = 'No se actualizaron datos';  
                    }
                
                }else{
                    $json['cod'] = 204;
                    $json['msg'] = 'El comprobante no puede ser modificado, por que ya fue informado';  
                }
                $json['cod'] = 200;
                $json['msg'] = 'Ok';
                $json['res']['lstNotas'] = $this->listaNotas($data['per']);

            }
        } catch (Exception $e) {
            $json['cod'] = 204;
            $json['msg'] = '';
        }
        return $json;
    }
    private function eliminarComprobante($data,$json){
        try {
            $validaStock = $this->validardStockDisponible(1,[],0,$data);
            $validaCp = $this->validarCpExiste('00',$data['selSerieCP'],$data['txtNumeroCP']);

            if( $validaCp['ind'] == 0){
                $json['cod'] = 204;
                $json['msg'] = 'Eliminar: El documento no existe';  
            } else if ($validaStock['ind']==1){ 
                $json['cod'] = 204;
                $json['msg'] = 'Error en las contidades de stock';
                $json['res'] = $validaStock['items'];             
            } else if ( $validaCp['ind']==1 ){
                if($validaCp['dataCpe'][0]['ind_informado'] == 0){
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

                    foreach( $validaStock['items'] as $validRow ) {
                        if(count($validRow)>0){ 
                            // Actualiza cantidades de stock he inventario y registra movmientos

                            $this->db->where('num_ruc',$this->rucEmpr);
                            $this->db->where('num_inventario',1);
                            $this->db->where('cod_producto',$validRow['cod']);
                            $inventario = $this->db->get('alma_inventario');
                            $dataInve = [];
                            foreach( $inventario->result_array() as $reg ){
                                $dataInve[] = $reg;
                            }

                            if (abs($validRow['cntDescontar'])>0 && count($dataInve)>0) {
                                $update = array(
                                    'cnt_stock' => (float)$dataInve[0]['cnt_stock']-$validRow['cntDescontar']
                                );
                                $where = array(   
                                    'num_ruc' => $this->rucEmpr,
                                    'num_inventario' => 1,
                                    'cod_producto' => $validRow['cod']
                                );
                                
                                $this->db->select_max('num_movimiento');
                                $this->db->where('num_ruc',$this->rucEmpr);
                                $this->db->where('num_inventario',1);
                                $this->db->where('cod_producto',$validRow['cod']);
                                $queryMax = $this->db->get('alma_movinve');
                                $numMaxMovInve;
                                $result = $queryMax->result_array();
                                if($result[0]['num_movimiento'] !== null){
                                    $numMaxMovInve =  $result[0]['num_movimiento']+1;
                                }else{
                                    $numMaxMovInve = 1;
                                }

                                $movimiento = array(   
                                    'num_ruc' => $this->rucEmpr,
                                    'num_inventario' => 1,
                                    'cod_producto' => $validRow['cod'],
                                    'num_movimiento' => $numMaxMovInve,
                                    'cnt_movimiento' => -1*$validRow['cntDescontar'], 
                                    'cnt_stockprev' => (float)$dataInve[0]['cnt_stock'], 
                                    'cnt_stockpost' => (float)$dataInve[0]['cnt_stock']-$validRow['cntDescontar'], 
                                    'cod_operacion' => '00', 
                                    'cod_cpe' => '00',
                                    'num_serie' => $data['selSerieCP'],
                                    'num_cpe' => $data['txtNumeroCP']
                                );
                                $this->db->update('alma_inventario', $update, $where);
                                $this->db->insert('alma_movinve',$movimiento);
                            }
                        }
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

        $array = array();     
        $query = $this->db->get();   
        $data = [];
        foreach( $query->result_array() as $reg ){
            $data[] = $reg;
        }
        if(count($data)>0){
            
            foreach( $data as $row ) {
                $this->db->select('*');
                $this->db->from('cont_cpedata');
                $this->db->where('num_ruc',trim($row['num_ruc']));
                $this->db->where('cod_cpe',trim($row['cod_cpe']));
                $this->db->where('num_serie',trim($row['num_serie']));
                $this->db->where('num_cpe',trim($row['num_cpe']));
                $this->db->order_by('num_item', 'ASC');
                $this->db->order_by('cod_rubro', 'ASC');

                $qry = $this->db->get(); 
                $items = [];
                $lstItem = array();
                $lstDetalle = array(
                    'dir' => '',
                    'obs' => ''                  
                );
                foreach( $qry->result_array() as $res ){
                    $items[] = $res;
                }
                if(count($items)>0){
                    $i=-1;
                    $j=0;
                    foreach( $items as $line ) {
                        if($line['num_item']==0) {
                            ($line['cod_rubro']=='01')? $lstDetalle['dir'] = $line['des_rubro'] : '';
                            ($line['cod_rubro']=='02')? $lstDetalle['obs'] = $line['des_rubro'] : '';
                        }else{
                            if ($j!==(int)$line['num_item']){
                                $j = (int)$line['num_item'];
                                $i++;
                            }
                            ($line['cod_rubro']==81)? $lstItem[$i]['cnt'] = $line['mto_rubro'] : '';
                            ($line['cod_rubro']==82)? $lstItem[$i]['und'] = $line['des_rubro'] : '';
                            ($line['cod_rubro']==83)? $lstItem[$i]['cod'] = $line['des_rubro'] : '';
                            ($line['cod_rubro']==84)? $lstItem[$i]['des'] = $line['des_rubro'] : '';
                            ($line['cod_rubro']==85)? $lstItem[$i]['pun'] = $line['mto_rubro'] : '';
                            ($line['cod_rubro']==99)? $lstItem[$i]['imp'] = $line['mto_rubro'] : '';
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
                    'dir' => $lstDetalle['dir'],
                    'obs' => $lstDetalle['obs'],
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
        $this->db->where('cod_estado','01'); // 01: Activo
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
    private function listaProductos() {   
        $this->db->select('*');
        $this->db->from('alma_listaproducto');
        $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la empresa xxx
        $this->db->where('num_inventario',1); //Tipo comprobante 00 - Nota de pedido
        $this->db->where('num_listaprod',1); //Tipo comprobante 00 - Nota de pedido
        $this->db->where('cod_estado','01'); //Tipo comprobante 00 - Nota de pedido
        //$this->db->limit(100); 
        $query = $this->db->get();  
        $list = [];
        foreach( $query->result_array() as $reg ){
            $list[] = $reg;
        }
        $array = array();
        if(count($list)>0){
            $listaProd = array();
            foreach( $list as $row ){
                array_push($listaProd,trim($row['cod_producto']));   
            }

            $this->db->select('*');
            $this->db->from('alma_producto');
            $this->db->where('num_ruc',$this->rucEmpr); //Comprobantes de la empresa xxx
            $this->db->where_in('cod_producto',$listaProd); //Tipo comprobante 00 - Nota de pedido
            $query = $this->db->get();  
            $data = [];
            foreach( $query->result_array() as $reg ){
                $data[] = $reg;
            }
            
            if(count($data)>0){
                foreach( $data as $row ){

                    $this->db->where('num_ruc',$this->rucEmpr);
                    $this->db->where('num_inventario',1);
                    $this->db->where('cod_producto',$row['cod_producto']);
                    $inventario = $this->db->get('alma_inventario');
                    $dataInve = [];
                    foreach( $inventario->result_array() as $reg ){
                        $dataInve[] = $reg;
                    }

                    $docs = array(
                        'cod' => trim($row['cod_producto']),
                        'des' => trim($row['des_producto']),
                        'lar' => trim($row['des_larga']),
                        'und' => trim($row['cod_unimed']),
                        'pun' => trim($dataInve[0]['mto_pventa']),
                        'est' => trim($row['cod_estado'])
                    );
                    $array[] = $docs;
                }
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
                $json['msg'] = 'Generar PDF: El documento no existe';
                echo json_encode($json);
            }else{
                $cpePdf = [];
                $list = [];
                foreach( $queryCp->result_array() as $row ){ $cpePdf[] = $row;} 
                $dataCpe = $cpePdf[0];
                $dataCpe['dir'] = '';
                $dataCpe['obs'] = '';
                $this->db->where('num_ruc',$this->rucEmpr);
                $this->db->where('cod_cpe','00');
                $this->db->where('num_serie',$data['selSerieCP']);
                $this->db->where('num_cpe',$data['txtNumeroCP']);
                $this->db->order_by('num_item', 'ASC');
                $this->db->order_by('cod_rubro', 'ASC');
                $qry = $this->db->get('cont_cpedata');
                foreach( $qry->result_array() as $row ){
                    if($row['cod_rubro']=='01') {$dataCpe['dir']= $row['des_rubro'];}
                    if($row['cod_rubro']=='02') {$dataCpe['obs']= $row['des_rubro']; }
                    if($row['cod_rubro']=='81') {$list[$row['num_item']]['cnt']= $row['mto_rubro'];}
                    if($row['cod_rubro']=='82') {$list[$row['num_item']]['und']= $row['des_rubro'];}
                    if($row['cod_rubro']=='83') {$list[$row['num_item']]['cod']= $row['des_rubro'];}
                    if($row['cod_rubro']=='84') {$list[$row['num_item']]['des']= $row['des_rubro'];}
                    if($row['cod_rubro']=='85') {$list[$row['num_item']]['pun']= $row['mto_rubro'];}
                    if($row['cod_rubro']=='99') {$list[$row['num_item']]['imp']= $row['mto_rubro'];}
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
                $pdf->Cell(58,4,'Direccion : '.$dataCpe['dir'],0,0,'L');

                $pdf->Ln(5);
                
                $pdf->Cell(22,4,'Descripcion','TB',0,'L');
                $pdf->Cell(7,4,'Cant','TB',0,'R');
                $pdf->Cell(7,4,'Und','TB',0,'L');
                $pdf->Cell(10.5,4,'P.U.','TB',0,'R');
                $pdf->Cell(13.5,4,'Importe','TB',0,'R');

                foreach($list as $key=>$itm){
                    if($key != 0){
                        $pdf->Ln();
                        $pdf->Cell(22,4,substr($itm['des'],0,12),0,0,'L');
                        $pdf->Cell(7,4,(float)$itm['cnt'],0,0,'R');
                        if(isset($itm['und'])){
                            $pdf->Cell(7,4,substr($itm['und'],0,4),0,0,'L');
                        }else{
                            $pdf->Cell(7,4,substr('---',0,4),0,0,'L');
                        }
                        $pdf->Cell(10.5,4,$itm['pun'],0,0,'R');
                        $pdf->Cell(13.5,4,$itm['imp'],0,0,'R');
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
                $pdf->Cell(43.5,4,'TOTAL',1,0,'R');
                $pdf->Cell(16.5,4,$dataCpe['mto_imptotal'],1,0,'R');
                
                $pdf->Ln(10);
                $pdf->Cell(58,4,'Gracias por su compra!',0,0,'C');
                $pdf->Ln(10);
                $pdf->SetFontSize(6);
                $pdf->Cell(58,4,'Fecha impresion: '.date("d-m-Y h:i:s"),0,0,'R');
                //$pdf->Ln();
                $pdf->SetFontSize(18);
                $pdf->Cell(58,4,' ',0,0,'L');
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
