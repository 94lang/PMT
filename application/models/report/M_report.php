<?php

class M_report extends CI_Model
{

function __construct(){
parent::__construct();
}

/// FUNGSI ADMIN BUAT CRUD PROJECT ............!!!!! HERE THE MODEL by fly
  function m_today_report($date){
                      $this->db->where('date_report',$date);
      return $query = $this->db->get('tabel_report')->result();
  }

  function M_last_report(){
      return $query = $this->db->query("SELECT from_id_user, date_report FROM tabel_report ")->result();
  }


  function m_page_report(){
           return   $query = $this->db->get('tabel_report')->result();
    }

  function m_list_report($id_user,$category){
        if($category == 'user'){
           return   $query = $this->db->query("SELECT DISTINCT id_report, date_report, nama_project
                                                FROM tabel_report a, tabel_project b, tabel_user c
                                                WHERE a.from_id_project = b.id_project  AND a.from_id_user = '$id_user'
                                                ORDER BY id_report DESC  ")->result();
        }else if($category == 'today'){
          $date = date('Y-m-d');
          return   $query = $this->db->query("SELECT DISTINCT id_report, date_report, nama_project
                                               FROM tabel_report a, tabel_project b, tabel_user c
                                               WHERE a.from_id_project = b.id_project AND a.from_id_user = '$id_user' AND date_report = '$date'
                                               ORDER BY id_report DESC ")->result();
        }else if($category == 'last'){
          return   $query = $this->db->query("SELECT DISTINCT id_report, date_report, nama_project
                                               FROM tabel_report a, tabel_project b, tabel_user c
                                               WHERE a.from_id_project = b.id_project AND a.from_id_user = '$id_user'
                                                    AND date_report = (SELECT MAX(date_report) as max FROM tabel_report WHERE from_id_user = '$id_user')
                                               ORDER BY id_report DESC ")->result();
        }

    }

    function m_report_task($id_project, $id_user){

            return $query = $this->db->query("SELECT *
                                                 FROM t_user_task a,tabel_task b
                                                 WHERE a.from_id_task = b.id_task
                                                    AND a.from_id_user = '$id_user'
                                                    AND b.from_id_project = '$id_project' ")->result();
    }

    function m_report_activity($id_user){
            return $query = $this->db->query("SELECT *
                                                 FROM tabel_activity
                                                 WHERE from_id_user = '$id_user' ")->result();
    }
    function m_report_list(){
        $date = date('Y-m-d');
            return $query = $this->db->query("SELECT *
                                                 FROM t_list_activity
                                                 WHERE status_list = 'done'
                                                    AND date_activity = '$date' ")->result();
    }

    function m_view_report($id_report){
            return $query = $this->db->query("SELECT id_report, date_report, keterangan, progress_on_report, status_on_report,
                                                     id_project, nama_project, start_date, finish_date, status_project, progress,
                                                     nama_user
                                                FROM tabel_report a, tabel_project b, tabel_user c
                                                WHERE a.from_id_project=b.id_project
                                                    AND a.from_id_user=c.id_user
                                                    AND a.id_report = '$id_report' ")->result();

    }
   function m_project_report($id_project){
        return $query = $this->db->query("SELECT id_report, date_report, id_user, nama_user
                                                FROM tabel_report a, tabel_user b
                                                WHERE a.from_id_user = b.id_user
                                                   AND a.from_id_project = '$id_project' ")->result();
    }

    function m_insert_report($values){
        $id_user    = $this->session->userdata('id_user');
        $date       = date('Y-m-d');
        $data = array(
                        'keterangan'            => $this->input->post('keterangan'),
                        'date_report'           => $date,
                        'from_id_user'          => $id_user,
                        'from_id_project'       => $this->input->post('id_project'),
                        'status_project'        => $this->input->post('status_project'),
                        'progress_project'      => $this->input->post('progress_project'),
                      );

                      if(isset($data['progress_project']) == null){
                        $data['progress_project'] = 0;
                      }
          //$this->db->set($values);
          $query = $this->db->query("INSERT INTO tabel_report(keterangan, date_report, from_id_project, from_id_user, status_on_report, progress_on_report)
                                      VALUES( '".$data['keterangan']."','".$data['date_report']."','".$data['from_id_project']."','".$data['from_id_user']."','".$data['status_project']."','".$data['progress_project']."')");
          if($query){
                  $result = 'ok';
            } else $result = 'failed';
      return $result;
    }
    function m_update_report($values){
      $id_user    = $this->input->post('id_user');
      $data = array(
                     'keterangan'            => $this->input->post('keterangan'),
                    );

                //$this->db->set($values);
        $query = $this->db->query("UPDATE tabel_report SET keterangan = '".$data['keterangan']."' ");

        if($query){
                 $result = 'ok';
          } else $result = 'failed';
      return $result;
    }
     function m_delete_report($id){                              // Hapus project by fly

            $this->db->query("DELETE FROM tabel_report
                                     WHERE id_report = '$id' ");
    }

}
