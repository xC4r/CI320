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
        $this->codi_usua = $this->session->userdata('codi_usua'); 
        $this->nomb_usua = $this->session->userdata('nomb_usua');
        if ($this->codi_usua == "") {
            header('location: login');
        }
    }


    public function admi_prog_list() {

    }
    public function menu_list_apli() {

    }
    
}
