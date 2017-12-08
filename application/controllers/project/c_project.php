<?php

class c_project extends CI_Controller
{

function __construct(){
parent::__construct();
$this->load->model('project/m_project');
$this->load->helper('url');
$this->load->database();
$this->load->library('session');

if ($this->session->userdata('akses') == null) {
				   redirect(base_url());http://localhost/PM/index.php/view/project/11
				}
}

// FUNGSI ADMIN BUAT CRUD PROJECT ............!!!!! HERE

	function index(){

			$data['all_p'] 		= $this->db->get('tabel_project')->num_rows();
													$this->db->where('status_project','on planning');
			$data['planning_p'] = $this->db->get('tabel_project')->num_rows();
												  $this->db->where('status_project','on progress');
			$data['progresh_p'] = $this->db->get('tabel_project')->num_rows();
													$this->db->where('status_project','done');
			$data['done_p'] 		= $this->db->get('tabel_project')->num_rows();

		if($this->session->userdata('akses') == 'admin'){
			$data['all_project'] = $this->m_project->m_list_project();
			$this->load->view('project/v_list_project', $data);
		}
		else if($this->session->userdata('akses') == 'user'){
			$id_user = $this->session->userdata('id_user');
			$data['all_user_project'] = $this->m_project->m_list_user_project($id_user);
			$this->load->view('project/v_user_project', $data);
		}


	}

	function add_project(){
		$data['all_candidat'] = $this->m_project->m_all_candidate();

		$data['aksi'] = 'add new';
		$this->load->view('project/v_form_project', $data);
	}

	function save_project(){
			 $aksi = $this->input->post('aksi');
			 $nama_project = $this->input->post('nama_project');
			 $start  = $this->input->post('start_date');
			 $finish = $this->input->post('finish_date');
			 $status = $this->input->post('status_project');
			 $nama   = $cek_members 	= $this->input->post('members');

			 if(ctype_space($nama_project) || $nama_project==null){
					$data['mssg'] = 'project name is empty!!';
			 }
			 else if(ctype_space($start) || $start==null){
					$data['mssg'] = 'start date project is empty!!';
			 }
			 else if(ctype_space($finish) || $finish==null){
					$data['mssg'] = 'finish date project is empty!!';
			 }
			 else if(ctype_space($status) || $status==null){
					$data['mssg'] = 'status project name is empty!!';
			 }
			 else{    //if all require field not empty and user for team choice
				 if(isset($cek_members)){
					 $cek_role 		= $this->input->post('role');
					 $n_members 		= count($cek_members);
							 $n_roles 	= count($cek_role);

							 if($n_members == $n_roles){
								 if($aksi == 'add new'){
									 $result = $this->m_project->m_insert_project($_POST);
									 if($result == 'ok'){
										 $data['mssg'] = 'sukses add new project';
									 }else {
										 $data['mssg'] = 'failed add new project';
									 }
								 }else if($aksi == 'edit'){
									 $result = $this->m_project->m_update_project($_POST);
									 if($result == 'ok'){
										 $data['mssg'] = 'sukses update data project';
									 }else {
										 $data['mssg'] = 'failed update data project';
									 }
								 }
								 $data['result'] = $result;
							 }else if($n_members > $n_roles){
									 $data['mssg'] = 'pastikan role user telah di tentukan!!';
							 }
						}
						else {				//if all require field not empty and not choice user for team
								if($aksi == 'add new'){
									 $result = $this->m_project->m_insert_project($_POST);
									 if($result == 'ok'){
										 $data['mssg'] = 'sukses add new project';
									 }else {
										 $data['mssg'] = 'failed add new project';
									 }
									 $data['result'] = $result;
							 }else if($aksi == 'edit'){
									 $result = $this->m_project->m_update_project($_POST);
									 if($result == 'ok'){
										 $data['mssg'] = 'sukses update data project';
									 }else {
										 $data['mssg'] = 'failed update data project';
									 }
							 }
							$data['result'] = $result;
						 }
			  }
		echo json_encode($data);
	}

	function view_project($id = 1){
	      $aksi = $this->uri->segment('1');//$this->input->POST('aksi');
	      $id = $this->uri->segment('3');

	    /*if($aksi == 'edit'){
	      $id = $this->input->POST('id');
	    }
	    else if(isset($aksi) == null){
	      $id = $this->uri->segment('2');//
	    }*/
	      /*    !!! cek user sebagai anggota team & rolenya dalamg project !!! by fly */
	      $id_user = $this->session->userdata('id_user');
	      $data['roles'] = $this->m_project->m_act_role($id,$id_user);
	      if(count($data['roles']) == 1){
	        $data['actor'] = 'team member';
	      }else $data['actor'] = 'not team member';


	      $data['data_project'] = $this->m_project->m_view_project($id);
	      $data['member'] = $this->m_project->m_view_p_members($id);
	      $data['task'] = $this->m_project->m_view_p_task($id);

	     if($aksi == 'edit'){
	      $data['aksi'] = 'edit';
	      $data['candidat'] = $this->m_project->m_candidate($id);

	      $this->load->view('project/v_form_project', $data);
	      }

	      else if(isset($aksi) == 'view'){
	        $this->load->view('project/v_info_project', $data);
	      }
	      //echo json_encode($data);
	    }

			function view_project_user($id = 1){
						$aksi = $this->uri->segment('1');
						$id = $this->uri->segment('3');

						/*    !!! cek user sebagai anggota team & rolenya dalamg project !!! by fly */
						$id_user = $this->session->userdata('id_user');
						$data['roles'] = $this->m_project->m_act_role($id,$id_user);
						if(count($data['roles']) == 1){
							$data['actor'] = 'team member';
						}else $data['actor'] = 'not team member';


						$data['data_project'] = $this->m_project->m_view_project($id);
						$data['member'] = $this->m_project->m_view_p_members($id);
						$data['task'] = $this->m_project->m_view_p_task($id);

					 if($aksi == 'edit'){
						$data['aksi'] = 'edit';
						$data['candidat'] = $this->m_project->m_candidate($id);

						$this->load->view('project/v_form_project', $data);
						}

						else if(isset($aksi) == 'view'){
							$this->load->view('project/v_info_project_user', $data);
						}
						//echo json_encode($data);
					}

	function delete_project(){
        $id = $this->input->post('id');

		$this->m_project->m_delete_project($id);
	}

}
?>
