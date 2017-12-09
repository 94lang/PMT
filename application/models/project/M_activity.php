<?php

class M_activity extends CI_Model
{

function __construct(){
parent::__construct();
}
function m_view_activity($id_activity){
    return $query = $this->db->query("SELECT id_activity, nama_activity, status_activity, from_id_task, id_user, nama_user
                                        FROM  tabel_user a, tabel_activity b
                                        WHERE a.id_user = b.from_id_user
                                            AND b.id_activity = $id_activity ")->result();
}

function m_user_task($value0){               // ambil daftar anggota yg di tugaskan untuk task ini

        return  $query = $this->db->query("SELECT id_user,nama_user
                                             FROM t_user_task a, tabel_user b
                                             WHERE a.from_id_user = b.id_user
                                                 AND from_id_task = '$value0'")->result();
    }

    function m_other_member_t($value0,$value1){               // ambil daftar anggota project yg tidah di tugaskan pada task 'ini'

          return  $query = $this->db->query("SELECT id_user,nama_user
                                             FROM tabel_user
                                             WHERE id_user IN (SELECT from_id_user
                                                                    FROM t_user_task
                                                                    WHERE from_id_task = '$value1')

                                                AND id_user NOT IN (SELECT from_id_user
                                                                    FROM tabel_activity
                                                                    WHERE id_activity = '$value0')")->result();
    }

function m_insert_activity($values){               // add new project
            $data = array(
                    'nama_activity' => $this->input->post('nama_activity'),
                    'member' => $this->input->post('member'),
                    'id_task' => $this->input->post('id_task')
                );

            //$this->db->set($values);
            $query = $this->db->query("INSERT INTO tabel_activity(nama_activity,status_activity,from_id_user,from_id_task)
                                    VALUES('".$data['nama_activity']."','on progress','".$data['member']."','".$data['id_task']."')");
                     $this->db->query("UPDATE tabel_task SET status_task = 'on progress' WHERE id_task = '".$data['id_task']."' ");
            if($query){
                 $id_task = $this->db->insert_id();
                 $list_activity = $this->input->post('activity_list');


                if(isset($list_activity)){
                    $n = count($list_activity);
                    $i =0;
                    for($i;$i<$n;$i++){
                        $this->db->query("INSERT INTO t_user_task(from_id_user,from_id_task)
                                                VALUES('".$list_activity[$i]."',' $id_task') ");
                    }
                }
                  $result = 'ok';
           }else  $result = 'no';
       return $result;
    }
function m_update_Nactivity($id_activity,$nama_activity){
  $query = $this->db->query("UPDATE tabel_activity SET nama_activity = '$nama_activity' WHERE id_activity = '$id_activity' ");
  if($query){$result = 'ok';}else  $result = 'failed';
 return $result;
}
function m_update_activity($values){      // Update data project

        $id = $this->input->post('id_activity');

         $data = array(
                    'nama_activity' => $this->input->post('nama_activity'),
                    'member' => $this->input->post('member'),
                );

            $query = $this->db->query("UPDATE tabel_activity SET nama_activity='".$data['nama_activity']."',from_id_user='".$data['member']."'
                                          WHERE id_activity='$id' ");

            if($query){$result = 'ok';}else  $result = 'no';
           return $result;


    }

    function m_delete_activity($id){                              //

            $this->db->query("DELETE FROM tabel_activity WHERE id_activity='$id' ");
    }

    function m_delete_list($id){                                // delete_list

            $this->db->query("DELETE FROM t_list_activity WHERE id_list='$id' ");
    }


  function m_list_status($id,$status){

      $date = date('Y-m-d');

            $this->db->where('id_list', $id);
            $this->db->set('status_list',$status);
            $this->db->set('date_activity',$date);
       $this->db->update('t_list_activity');

        $n_all = $this->db->query("SELECT id_list FROM t_list_activity WHERE from_id_activity = (SELECT from_id_activity FROM t_list_activity WHERE id_list = '$id') ")->num_rows();
        $n_done = $this->db->query("SELECT id_list FROM t_list_activity   WHERE from_id_activity =  (SELECT from_id_activity FROM t_list_activity WHERE id_list = '$id')  AND status_list = 'done' ")->num_rows();

        $id_activity = 'none';
        if($n_all == $n_done){
            $query = $this->db->query("SELECT from_id_activity FROM t_list_activity WHERE id_list = '$id' ")->result();
            foreach($query as $key){
              $id_activity = $key->from_id_activity;
            }
          $this->db->query("UPDATE tabel_activity SET status_activity = 'done' WHERE id_activity = '$id_activity' ");
        }
        return $id_activity;
      }
      function m_task_status($id_activity){
        $n_all = $this->db->query("SELECT id_activity FROM tabel_activity WHERE from_id_task = (SELECT from_id_task FROM tabel_activity WHERE id_activity = '$id_activity') ")->num_rows();
        $n_done = $this->db->query("SELECT id_activity FROM tabel_activity   WHERE from_id_task =  (SELECT from_id_task FROM tabel_activity WHERE id_activity = '$id_activity')  AND status_activity = 'done' ")->num_rows();

        $id_task = 'none';
        if($n_all == $n_done){
            $query = $this->db->query("SELECT from_id_task FROM tabel_activity WHERE id_activity = '$id_activity' ")->result();
            foreach($query as $key){
              $id_task = $key->from_id_task;
            }
          $this->db->query("UPDATE tabel_task SET status_task = 'done' WHERE id_task = $id_task ");
        }
        return $id_task;
      }



  function m_insert_list($values){
         $status = null;
         $date = null;

        $nama_list    = $this->input->post('nama_list');
        $id_activity  = $this->input->post('id_activity');

        $status = $this->input->post('status_list');
          $date = date('Y-m-d');
        if($status == null) {
          $status = 'on progresh';
        }

        $query = $this->db->query("INSERT INTO t_list_activity(list_activity,status_list,from_id_Activity,date_activity)
                                    VALUES('$nama_list','$status','$id_activity','$date') ");

        if($query){
          $result = $this->db->insert_id();
           $this->db->query("UPDATE tabel_activity SET status_activity = 'on progress' WHERE id_activity = '$id_activity' ");
        }else  $result = 'failed';
                                   return $result;
    }
    function m_update_list($values){

        $id_list      = $this->input->post('id_list');
        $nama_list    = $this->input->post('nama_list');
        //$id_activity  = $this->input->post('id_activity');

        $status = $this->input->post('status_list');
          $date = date('Y-m-d');
        if($status == null) {
          $status = 'on progresh';
        }


          $query = $this->db->query("UPDATE t_list_activity
                                SET list_activity='$nama_list', status_list='$status', date_activity='$date'
                                WHERE id_list =' $id_list' ");
            if($query){$result = 'ok';}else  $result = 'failed';
                return $result;
    }
    function m_task_activity($value0){               //
                            //$this->db->where('from_id_task',$value0)
          return  $query =  $this->db->query("SELECT id_activity, nama_activity, status_activity, nama_user, id_user
                                                FROM tabel_activity a, tabel_user b
                                                WHERE a.from_id_user = b.id_user
                                                    AND a.from_id_task = '$value0'
                                                ORDER BY id_activity ASC ")->result();
    }
    function m_list_activity(){               //
              $this->db->order_by('id_list','ASC');
              return  $query =  $this->db->get('t_list_activity')->result();
    }
}



?>
