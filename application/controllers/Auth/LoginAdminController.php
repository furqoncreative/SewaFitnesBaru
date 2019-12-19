<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginAdminController extends CI_Controller {
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if($this->session->logged_in_admin){
            redirect(base_url('index.php/admin'));
        }
        $this->load->model('UsersModel');
    }

    /**
     * Set validation rule for Login form
     */
    public function setValidationRules()
    {
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password','Password','trim|required');
    }

    /**
     * Show Login form. Attempt Login if POST request.
     */
    public function showLoginForm()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            //Set Validation rules
            $this->setValidationRules();
            if($this->form_validation->run())
            {
                $email = $this->input->post('email');
                //Encrypt password using MD5
                $password = md5($this->input->post('password'));
                $user = $this->UsersModel->attemptLoginAdmin($email,$password);
                if($user){
                    //Set user session
                    $this->setUserSession($user);

                    //If user attempted to access session protected page.
                    $redirect_url = $this->session->userdata('redirect_to');
                    if($redirect_url){
                        redirect($redirect_url);
                    }
                    else{
                        redirect(base_url('index.php/admin'));
                    }

                }
                else{
                    //Invalid credentials.
                    $this->session->set_flashdata('error', 'Email or password incorrect');
                    //Redirect back
                    redirect($_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
        }
        $this->load->view("admin/auth/login");
    }

    /**
     * Set Users Login session
     *
     * @param $user
     */
    private function setUserSession($user)
    {

        $logged_in_admin = array(
            'admin'  => $user,
            'logged_in_admin' => TRUE
        );
        $this->session->set_userdata($logged_in_admin);
    }
}
