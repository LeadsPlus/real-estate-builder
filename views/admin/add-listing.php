<?php 

$listings = PL_Config::PL_API_LISTINGS('create');
// $listings = array();
  PL_Form::generate($listings['args'], false, "POST", "pls_admin_add_listing", true);