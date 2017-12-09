<?php

class M_project extends CI_Model
{

function __construct(){
parent::__construct();
}

/// FUNGSI ADMIN BUAT CRUD PROJECT ............!!!!! HERE THE MODEL
    function m_list_project(){                // Ambil daftar project
            $this->db->order_by('id_project','desc');
           return $query = $this->db->get('tabel_project')->result();

    }
    function m_list_user_project($id_user){                // Ambil daftar project user

           return $query = $this->db->query("SELECT * FROM tabel_project a, t_team_project b
                                                WHERE a.id_project = b.from_id_project
                                                AND b.from_id_user = '$id_user' ")->result();

    }

    function m_act_role($id_project,$id_user){          // Ambil role user dalam project
            return $query = $this->db->query("SELECT role
                                                FROM t_team_project
                                                WHERE from_id_user = '$id_user'
                                                AND from_id_project = '$id_project' ")->result();
    }

    function m_all_candidate(){
        return $query = $this->db->get('tabel_user')->result();
    }


     function m_insert_project($values){               // add new project
       $data = array(
           'nama_project'      => $this->input->post('nama_project'),
           'start_date'        => $this->input->post('start_date'),
           'finish_date'       => $this->input->post('finish_date'),
           'status_project'    => $this->input->post('status_project')
           );

       //$this->db->set($values);
       $query = $this->db->query("INSERT INTO tabel_project(nama_project,start_date,finish_date,status_project)
                               VALUES('".$data['nama_project']."','".$data['start_date']."','".$data['finish_date']."','".$data['status_project']."')");

         if($query){
           $id_project = $this->db->insert_id();
           $members   = $this->input->post('members');
           $role      = $this->input->post('role');

           if(isset($members)){
              $n = count($members);
              $i =0;
              for($i;$i<$n;$i++){
                  $this->db->query("INSERT INTO t_team_project(from_id_user,from_id_project,role)
                                          VALUES('".$members[$i]."',' $id_project','".$role[$i]."') ");
              }
           }
             $msg = 'ok';
         }else $msg = 'failed';
      return $msg;
    }

    function m_view_project($value0){                // ambil data project
             $this->db->WHERE('id_project',$value0);
            return $query = $this->db->get('tabel_project')->result();
        /*
            foreach ($query->result() as $u) {
                $data['id_project'] = $u->id_project;
                $data['nama_project'] = $u->nama_project;
                $data['start_date'] = $u->start_date;
                $data['finish_date'] = $u->finish_date;
                $data['progresh'] = $u->progresh;
                $data['status_project'] = $u->status_project;
            }

         return $data; */
    }

   function m_candidate($value0){
        return $query = $this->db->get('tabel_user')->result();
    }

    function m_view_p_members($value0){               // ambil daftar anggota per project

         return   $query = $this->db->query("SELECT id_user,nama_user,role
                                        FROM t_team_project a, tabel_user b
                                        WHERE a.from_id_user = b.id_user
                                            AND from_id_project = '$value0'")->result();
    }

    function m_view_p_task($value0){               // ambil daftar task per project

         return   $query = $this->db->query("SELECT * FROM tabel_task
                                                      WHERE from_id_project = '$value0'")->result();
    }
    function m_user_task($id){
      return   $query = $this->db->query("SELECT nama_user, from_id_task, from_id_user FROM t_user_task a, tabel_user b
                                                   WHERE a.from_id_user = b.id_user
                                                   AND from_id_task in (SELECT id_task FROM tabel_task WHERE from_id_project = '$id')")->result();
    }
    function m_update_project($values){      // Update data project
          $id = $this->input->post('id_project');
              $data = array('nama_project' => $this->input->post('nama_project'),  'start_date' => $this->input->post('start_date'),'finish_date' => $this->input->post('finish_date'),'status_project' => $this->input->post('status_project'));

              $query = $this->db->query("UPDATE tabel_project SET nama_project='".$data['nama_project']."', start_date='".$data['start_date']."', finish_date='".$data['finish_date']."', status_project='".$data['status_project']."' WHERE id_project='$id' ");
              if($query){
               $members   = $this->input->post('members');
               $role      = $this->input->post('role');
               $initial_d = 0;
              if(isset($members)){
                  //$n = count($members);
                  $i = 0;
                  foreach($members as $id_user){

                              $this->db->where('from_id_project',$id);
                              $this->db->where('from_id_user',$id_user);
                      $cek = $this->db->get('t_team_project')->num_rows();

                      if($cek < 1){
                          $this->db->query("INSERT INTO t_team_project(from_id_user,from_id_project,role)
                                              VALUES('$id_user','$id','".$role[$i]."') ");
                      }
                      else {
                          $this->db->query("UPDATE t_team_project SET role = '".$role[$i]."'
                                                  WHERE from_id_project = '$id'
                                                  AND from_id_user = '".$id_user."' ");
                      }
                      $i++;


                  }
                  $initial_d = implode(',',$members);

                  }

                  $this->db->query("DELETE FROM t_team_project
                                              WHERE from_id_project = '$id'
                                                  AND from_id_user NOT IN ($initial_d) ");
                  $msg = 'ok';
            }else $msg = 'failed';
        return $msg;
    }
    function m_update_status($id_project){
      $n_all = $this->db->query("SELECT id_task FROM tabel_task WHERE from_id_project = '$id_project' ")->num_rows();
      $n_done = $this->db->query("SELECT id_task FROM tabel_task   WHERE from_id_project =  '$id_project'  AND status_task = 'done' ")->num_rows();


      if($n_done == 0){
        $this->db->query("UPDATE tabel_project SET status_project = 'on planning', progress = 0 WHERE id_project = $id_project ");
      }else if($n_all > $n_done){
          $des = $n_done / $n_all;
          $des = $des*100;
          $progress = floor($des);
          $this->db->query("UPDATE tabel_project SET status_project = 'on progress', progress = '$progress' WHERE id_project = $id_project ");
      }else if($n_all == $n_done){
        $this->db->query("UPDATE tabel_project SET status_project = 'done', progress = 100 WHERE id_project = $id_project ");
      }

    }

    function m_delete_project($id){                              // Hapus project
            $this->db->query("DELETE FROM tabel_project
                                     WHERE id_project='$id' ");
    }

}

?>
