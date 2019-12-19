<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogoutAdminController extends CI_Controller {
    /**
     * LogoutController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if(!$this->session->logged_in_admin){
            redirect(base_url('index.php/login-admin'));
        }
    }

    /**
     * Destroy user session
     *
     * @param $user
     */
    public function logout()
    {
        $this->session->unset_userdata('admin');
        $this->session->unset_userdata('logged_in_admin');
        redirect(base_url('index.php/login-admin'));
	}
}
