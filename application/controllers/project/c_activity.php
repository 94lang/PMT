<?php

class c_activity extends CI_Controller
{

function __construct(){
parent::__construct();
$this->load->model('project/m_activity');
$this->load->helper('url');
$this->load->database();
$this->load->library('session');
}

// FUNGSI BUAT CRUD ACTIVITY ............!!!!! HERE


  function add_activity(){
    $data['id_task'] = $this->input->POST('id');
    $data['role'] = $this->input->POST('role');
    $data['actor']    = $this->input->POST('actor');
    $data['aksi'] = $this->input->POST('aksi');

    if($data['role'] == 'project leader'){
      $data['member'] = $this->m_activity->m_user_task($data['id_task']);
    }else if($data['role'] == 'project developer'){   $data['member'] = null; }

    //echo json_encode($data);
    $this->load->view('project/v_form_activity', $data);
  }

  function activity_list(){
    $data['id_task']  =$this->input->POST('id');
    $data['role']     =$this->input->POST('role');
    $data['actor']    = $this->input->POST('actor');

    $data['activityes'] = $this->m_activity->m_task_activity($data['id_task']);
    $data['list'] = $this->m_activity->m_list_activity();

    $this->load->view('project/v_activity', $data);
  }

  function view_activity(){
    $data['id_task'] = $this->input->POST('id_t');
    $data['id_actifity'] = $this->input->POST('id');
    $data['aksi'] = $this->input->POST('aksi');

    $data['data_actifity'] = $this->m_activity->m_view_activity($data['id_actifity']);

    if($data['aksi'] == 'edit'){
      $data['role'] = $this->input->POST('role');
      $data['member'] = $this->m_activity->m_other_member_t($data['id_actifity'],$data['id_task']);
      $this->load->view('project/v_form_activity', $data);
    }
  }
  function insert_activity(){
    $nama_activity = $this->input->POST('nama_activity');
    $data['id_list'] =   0;
    if(ctype_space($nama_activity) || $nama_activity==null){
      $data ['mssg'] = 'name of activity is empty!!';
    }else{
      $result = $this->m_activity->m_insert_activity($_POST);
      if($result != 'failed'){
        $data['id_list'] =   $result;

        $data ['mssg'] = 'success full add new activity';
      }else if($result == 'failed'){
        $data ['mssg'] = 'failed add new activity!!';
      }
      $data ['result'] = $result;
    }

    echo json_encode($data);

      //echo $result;
  }
  function update_Nactivity(){
    $id_activity = $this->input->POST('id');
    $nama_activity = $this->input->POST('nama');

    if(ctype_space($nama_activity) || $nama_activity==null){
      $data ['mssg'] = 'name of activity is empty!!';
    }else{
      $result = $this->m_activity->m_update_Nactivity($id_activity,$nama_activity);
      if($result == 'ok'){
        $data ['mssg'] = $result;//'success full update data activity';
      }else if($result == 'failed'){
        $data ['mssg'] = $result;//'failed update data activity!!';
      }
      $data ['result'] = $result;
    }

    echo json_encode($data);

  }
  function update_activity(){
    $nama_activity = $this->input->POST('nama_activity');

    if(ctype_space($nama_activity) || $nama_activity==null){
      $data ['mssg'] = 'name of activity is empty!!';
    }else{
      $result = $this->m_activity->m_update_activity($_POST);
      if($result == 'ok'){
        $data ['mssg'] = 'success full update data activity';
      }else if($result == 'failed'){
        $data ['mssg'] = 'failed update data activity!!';
      }
      $data ['result'] = $result;
    }

    echo json_encode($data);
  }

  function delete_activity(){
        $id = $this->input->post('id');

    $this->m_activity->m_delete_activity($id);
  }


///section list activity HERE by fly

  function add_list(){
    $data['aksi'] ='add new';
    $data['id_activity'] = $this->input->post('id');
    $this->load->view('project/v_form_list', $data);
  }
  function view_list(){
    $data['id_list'] = $this->input->POST('id');
    $data['aksi'] ='edit';

               $this->db->where('id_list',$data['id_list']);
    $data['data_list'] = $this->db->get('t_list_activity')->result();

    //if($data['aksi'] == 'edit'){
      $this->load->view('project/v_form_list', $data);
    //}
  }

  function delete_list(){
        $id = $this->input->post('id');

    $this->m_activity->m_delete_list($id);
  }

  function list_status(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');

    $this->m_activity->m_list_status($id,$status);
  }

  function insert_list(){
    $nama_list = $this->input->post('nama_list');

    if(ctype_space($nama_list) || $nama_list==null){
      $data ['mssg'] = 'name of list activity is empty!!';
    }else{
       $result = $this->m_activity->m_insert_list($_POST);
      if($result == 'ok'){
        $data ['mssg'] = 'success full add new list activity ';
      }else if($result == 'failed'){
        $data ['mssg'] = 'failed add new list activity !!';
      }
      $data ['result'] = $result;
    }
    echo json_encode($data);

  }

  function update_list(){
    $nama_list = $this->input->post('nama_list');

    if(ctype_space($nama_list) || $nama_list==null){
      $data ['mssg'] = 'name of list activity is empty!!';
    }else{
      $result = $this->m_activity->m_update_list($_POST);
      if($result == 'ok'){
        $data ['mssg'] = 'success full update data  list activity ';
      }else if($result == 'failed'){
        $data ['mssg'] = 'failed update data  list activity !!';
      }
      $data ['result'] = $result;
    }
    echo json_encode($data);
  }


/*
  /* Ujicoba misil :v
  function test_load(){
    $this->load->view('welcome_message.php');
  }*/

//}

}

?>
