<?php
defined('BASEPATH') OR exit('No direct script access allowed');

get_instance()->load->library('form_validation');

function setValidationRules($field, $label, $rules) {
  $CI = get_instance();
  $CI->form_validation->set_rules($field, $label, $rules);
  $value = $CI->input->post($field);
  $CI->session->set_flashdata($field . 'OldValue', $value);
  return $value;
}

function isValidationAnyInvalidField() {
  $CI = get_instance();
  if(!$CI->form_validation->run()) {
    $CI->session->set_flashdata('validationErrors', validation_errors('<p class="alert alert-warning">', '</p>'));
    return true;
  }
}

function getValidationErrors() {
  return get_instance()->session->flashdata('validationErrors');
}

function setValidationError($message) {
  return get_instance()->session->set_flashdata('validationErrors', "<p class='alert alert-danger'>$message</p>");
}

function getValidationSuccess() {
  return get_instance()->session->flashdata('successMessages');
}

function setValidationSuccess($message) {
  return get_instance()->session->set_flashdata('successMessages', "<p class='alert alert-success'>$message</p>");
}

function getValidationField($field) {
  return get_instance()->session->flashdata($field . 'OldValue');
}
