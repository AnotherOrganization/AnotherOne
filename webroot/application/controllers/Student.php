<?php defined("BASEPATH") or exit("No direct script access allowed");
/**
* 	TODO: complete description
*/
class Student extends App_Base_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	function profile(){

	}

	function enroll(){
		$this->load->view('layouts/header.php');
		$this->load->view('student/scheduler.php');
		$this->load->view('layouts/footer.php');
	}

	function view($semester){

	}

}
?>
