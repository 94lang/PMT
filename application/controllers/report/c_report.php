<?php

class c_report extends CI_Controller
{

function __construct(){
parent::__construct();
$this->load->model('project/m_project');
$this->load->model('report/m_report');
$this->load->helper('url');
$this->load->database();
$this->load->library('session');

if ($this->session->userdata('akses') == null) {
				   redirect(base_url());
				}
}

	function index(){

		$data['akses']	 = $this->session->userdata('akses');
    $date = date('Y-m-d');

		if($data['akses'] == 'admin'){
													$this->db->select('id_user');
													$this->db->select('nama_user');
			$data['list_user'] = $this->db->get('tabel_user')->result();
															$this->db->select('from_id_user');
															$this->db->select('date_report');
															$this->db->order_by('from_id_user','asc');
      $data['list_report'] = $this->db->get('tabel_report')->result();
      $data['today_report'] = $this->m_report->m_today_report($date);

		}
    /*
		else if($data['akses'] == 'user'){
			$id_user	 		 = $this->session->userdata('id_user');
			$data['list_report'] = $this->m_report->m_list_report($id_user);
		}*/
		$this->load->view('report/v_page_report', $data);
		//echo json_encode($cek);


	}

	function list_report($id = 1){
		$id_user 	= $this->uri->segment(4);
		$category = $this->uri->segment(2);
								$this->db->where('id_user', $id_user);
		$data['user']	= $this->db->get("tabel_user")->result();
		//if($category == 'user'){
						$data['list_report'] = $this->m_report->m_list_report($id_user,$category);
		//}


		$this->load->view('report/v_list_report', $data);


	}

  function requesto_create_report(){
			$id = $this->input->POST('id');
			$id_user = $this->session->userdata('id_user');
			$date 	 = date('Y-m-d');


		  				   $this->db->select('id_report');
          	 			   $this->db->where('from_id_project', $id);
          	 			   $this->db->where('from_id_user', $id_user);
          	 			   $this->db->where('date_report', $date);
          	 $on_preport = $this->db->get('tabel_report')->num_rows();

          	 if($on_preport <= 0 || $on_preport == null){
							  $this->m_project->m_update_status($id);

			  		 		echo $konfirmasi = 'ok';
           	}else if($on_preport > 0 || $on_preport != null){
           		echo $konfirmasi = 'report telah dibuat, tidak dapat membuat baru silahkan edit dari report yang telah anda buat!';
           	}

	}

	function create_report(){
		$data['aksi'] = 'add new';
		$id_user = $this->session->userdata('id_user');
		$id = $this->uri->segment('3');
		$data['data_project'] = $this->m_project->m_view_project($id);

			$data['task'] =$this->m_report->m_report_task($id,$id_user);
			$data['n_task'] = count($data['task']);
			$data['activity'] =$this->m_report->m_report_activity($id_user);
			$data['n_activity'] = count($data['activity']);
			$data['list'] =$this->m_report->m_report_list();
			$data['n_list'] = count($data['list']);

			$this->load->view('report/v_form_report', $data);
	}
	function view_report($id = 1){
		$id = $this->uri->segment('3'); // $this->input->POST('id');
		$data['aksi'] = $this->uri->segment('1'); // $this->input->POST('aksi');

		$data['data_project'] = $this->m_report->m_view_report($id);

					 $this->db->select('from_id_project');
            		 $this->db->select('from_id_user');
            		 $this->db->where('id_report', $id);
            $get_id= $this->db->get('tabel_report');
            foreach ($get_id->result() as $ku) {
                $id_project = $ku->from_id_project;
                $id_user = $ku->from_id_user;
            }
			  	$data['task'] =$this->m_report->m_report_task($id_project,$id_user);
			  	$data['n_task'] = count($data['task']);
			  	$data['activity'] =$this->m_report->m_report_activity($id_user);
			  	$data['n_activity'] = count($data['activity']);
			  	$data['list'] =$this->m_report->m_report_list();
			  	$data['n_list'] = count($data['list']);

		if($data['aksi'] == 'edit'){
			//echo json_encode($data);
			$this->load->view('report/v_form_report', $data);
		}else{
			//echo json_encode($data);
			$this->load->view('report/v_info_report', $data);
		}
	}

	function report_project($id = 1){
		$id_project = $this->uri->segment('3');
		$data['data_report'] = $this->m_report->m_project_report($id_project);

		//echo json_encode($data);
		$this->load->view('report/v_form_report', $data);

	}

	function insert_report(){
			$result = $this->m_report->m_insert_report($_POST);
		if($result == 'ok'){
				$data['mssg'] = 'success create report' ;
		}else if($result == 'failed'){
			$data['mssg'] = 'failed create report' ;
		}
		echo json_encode($data);
	}
	function update_report(){
		$result = $this->m_report->m_update_report($_POST);
		if($result == 'ok'){
				$data['mssg'] = 'success update report' ;
		}else if($result == 'failed'){
			$data['mssg'] = 'failed update report' ;
		}
		echo json_encode($data);
	}
	function delete_report(){
        $id = $this->input->post('id');

		$this->m_report->m_delete_report($id);
	}
}
