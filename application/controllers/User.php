<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('User_model', 'users');
  }

  public function index() {
    $user = $this->system->getUser();

    if($user)
      $this->load->template('user/home');
    else
      redirect('/login');
  }

  public function login() {
    $ip = $this->input->ip_address();

    $user = $this->users->findByIp($ip);

    if($user && $user->remember) {
      $this->system->setUser($user);
      redirect('/');
      return;
    }

    $data['email'] = getValidationField('email');
    $data['validationErrors'] = getValidationErrors();
    $data['successMessages'] = getValidationSuccess();
    $this->load->view('user/login', $data);
  }

  public function logout() {
    $user = $this->system->getUser();

    if($user && $user->id) {
      $user->remember = false;
      $this->users->update($user);
    }

    $this->session->sess_destroy();

    redirect('/');
  }

  public function register() {
    $data['name'] = getValidationField('name');
    $data['email'] = getValidationField('email');
    $data['password'] = getValidationField('password');
    $data['passwordCheck'] = getValidationField('passwordCheck');
    $data['agreeTerms'] = getValidationField('agreeTerms');
    $data['validationErrors'] = getValidationErrors();
    $this->load->view('user/register', $data);
  }

  public function authenticate() {
    $email = setValidationRules('email', 'Email', 'trim|required|valid_email');
    $password = setValidationRules('password', 'Password', 'trim|required');
    $remember = $this->input->post('remember');

    if(isValidationAnyInvalidField()) {
      redirect('/login');
      return;
    }

    $user = $this->users->findByEmailAndPassword($email, $password);

    if(!$user) {
      setValidationError('User email and/or password incorrect.');
      redirect('/login');
      return;
    }

    $user->ip = $this->input->ip_address();
    $user->remember = $remember === 'on';

    $this->users->update($user);

    $this->system->setUser($user);

    redirect('/');
  }

  public function create() {
    $user = new stdClass();
    $user->name = setValidationRules('name', 'Full name', 'trim|required');
    $user->email = setValidationRules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
    $user->password = setValidationRules('password', 'Password', 'trim|required|min_length[8]');
    $user->passwordCheck = setValidationRules('passwordCheck', 'Retype password', 'trim|required|matches[password]');

    if(isValidationAnyInvalidField()) {
      redirect('/register');
      return;
    }

    $this->users->insert($user);

    setValidationSuccess("Your account was created. Please enter your password.");

    redirect('/login');
  }

  public function reset() {
    $this->load->library('email');

    $this->email->from('no-reply@template.com', 'Template');
    $this->email->to('username@email.com');
    $this->email->subject('Account Recover!');
    $this->email->message('A recover link.');
    $this->email->send();
  }

}
