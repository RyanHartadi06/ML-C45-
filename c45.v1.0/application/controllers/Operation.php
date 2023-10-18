<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operation extends CI_Controller {
	function __construct() {
    parent::__construct();
		if($this->session->userdata('login')===NULL){
			redirect('home');
		}
	}
	public function savedata(){
		if($this->input->is_ajax_request()){
			$index=$this->input->post('index');
			$data=$this->input->post('data');
			$this->session->set_userdata('process_datasetindex',$index);
			$this->session->set_userdata('process_dataset',$data);
		}else{
			show_404();
		}
	}
	public function savedataset(){
		$config['upload_path']          = './assets/uploads/';
		$config['allowed_types']        = 'xlsx|xls';
		$config['file_name']            = "dataset";
		$config['max_size']             = 5000;
		$config['overwrite']            = true;
		$this->load->library('upload', $config);
		if($this->upload->do_upload('files')){

		}
		redirect("c45/process");
	}
	public function deletedataset(){
		unlink('./assets/uploads/dataset.xlsx');
		unlink('./assets/uploads/dataset.xls');
		redirect("c45/process");
	}
}
