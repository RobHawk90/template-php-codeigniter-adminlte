<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

  public function template($urlView, $data = array(), $shouldReturnHtmlAsString = false) {
    $templateData['systemUser'] = get_instance()->system->getUser();

    if($shouldReturnHtmlAsString) {
      $htmlAsString = $this->view('header');
      $htmlAsString .= $this->view('top-navbar', $templateData);
      $htmlAsString .= $this->view($urlView, $data);
      $htmlAsString .= $this->view('left-navbar', $templateData);
      $htmlAsString .= $this->view('right-navbar');
      $htmlAsString .= $this->view('footer');
      return $htmlAsString;
    }

    $this->view('header');
    $this->view('top-navbar', $templateData);
    $this->view('left-navbar', $templateData);
    $this->view($urlView, $data);
    $this->view('right-navbar');
    $this->view('footer');
  }

}