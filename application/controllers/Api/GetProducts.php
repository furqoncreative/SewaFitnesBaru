<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class GetProducts extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model("ProductsModel");
    }

    function index_get() {
		$r = $this->ProductsModel->getAllProducts();

        $this->response($r, 200);
    }    

    function index_post(){
		$this->response(404);

     
    }

}

?>
