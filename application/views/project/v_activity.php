<?php

foreach ($activityes as $u4) {
      $id_activity 		= $u4->id_activity;
      $nama_activity 		= $u4->nama_activity;
      $nama_user 			= $u4->nama_user;
      $id_user 			= $u4->id_user ;
      echo'
      <div class="card" style="border: 0.5px solid #e7e4e4ba !important">
        <div class="card-header" role="tab" id="heading'.$id_activity.'" style="border-bottom:0.5px solid #e7e4e4ba !important;">
          <div class="input-group">
            <input type="text" style="cursor:pointer;" id="nama_activity_update'.$id_activity.'" class="form-control act activity'.$id_activity.'" value="'.$nama_activity.'"
            <a data-toggle="collapse" href="#collapse'.$id_activity.'" aria-expanded="true" aria-controls="collapseOne" style="text-decoration:none;color:#4e4f50 !important">
            </a>
            <span class="input-group-addon activity">
            <button class="act editactivity'.$id_activity.'" onclick="activeInput('.$id_activity.')"><i style="  font-size: 1rem !important;" class="material-icons">mode_edit</i></button>
            <button class="saved saveactivity'.$id_activity.'" onclick="saveInput('.$id_activity.')"><i style="  font-size: 1rem !important;" class="material-icons">save</i></button>
              <button class="act deleteactivity'.$id_activity.'" onclick="delete_activity('.$id_activity.')"><i style="  font-size: 1rem !important;" class="material-icons">delete</i></button>
            </span>
          </div>
        </div>' ;
        echo'<div id="collapse'.$id_activity.'" class="collapse" role="tabpanel" aria-labelledby="heading'.$id_activity.'" data-parent="#accordion">
        <div class="card-body">';
      foreach ($list as $u5) {
        if($u5->from_id_activity == $id_activity){
          if($u5->status_list == 'done'){
            echo '<li style="font-size:0.88rem;list-style:none;">'.$u5->list_activity;

                if($actor == 'on task' || $role == "project leader"){
                echo'<span class="ml-3"><input type="checkbox" name="status[]" value="'.$u5->id_list.'" class="list" checked></input></span>

                  <span class="ml-3"><button  class="project" onclick="delete_list('.$u5->id_list.')"><i class="material-icons">delete</i></button></span>
                  </li>
                  <br/>
                  ';
              }
          }
          else {
            echo '<li style="font-size:0.88rem;list-style:none;">'.$u5->list_activity;
                if($actor == 'on task' || $role == "project leader"){
                echo'<span class="ml-3"><input type="checkbox" name="status[]" value="'.$u5->id_list.'" class="list" ></input></span>

                <span class="ml-3"><button class="project" onclick="delete_list('.$u5->id_list.')"><i class="material-icons">delete</i></button></span>
                </li>
                <br/>
                ';
              }
          }
        }
      }
      //if($id_user  == $this->session->datauser('id_user'); || $role == "project leader"){
      echo
       '      <div id="top_ofBoard'.$id_activity.'"></div>
              <div class="input-group input-group-sm">
                <input type="hidden" class="form-control" id="id_list" name="id_list" value="" placeholder="id activity">
                <input type="text" id="nama_list'.$id_activity.'" name="nama_list'.$id_activity.'" class="form-control" placeholder="Add new list">
                <button class="input-group-addon" id="basic-addon2" onclick="save_list('.$id_activity.')">Add  List</button>
              </div>
          ';
        //}
        echo'
          </div>
       </div>
    </div>
';
};

?>
<script>
  $(document).ready(function(){
    $('.saved').css('display','none');
    $('.act').css('background','#f8f9fa');
    $('.act').css('border','none');
    $('.act').css('border-radius','0 ');

    $(".list").change(function() {
        if(this.checked) {
          var id = $(this).val();
          var status = 'done';
            $.ajax({
            type:"POST",
            dataType : "JSON",
            data : {id:id,status:status},
            url: "<?php echo base_url('index.php/project/c_activity/list_status'); ?>",
            success: function(data){

            }
      });
        }
    });
  });
</script>
<script type="text/javascript">
	function save_list(id_activity){
			nama_list = $('#nama_list'+String(id_activity)).val();
			console.log(nama_list,nama_list);
			$.ajax({
								type:"POST",
								dataType : "JSON",
								data: {id_activity:id_activity, nama_list:nama_list},
								url: "<?php echo base_url('index.php/project/c_activity/insert_list'); ?>",
								success: function(data){
                      if(data['id_list'] != 0){
                        $('#top_ofBoard'+String(id_activity)).append('<li style="font-size:0.88rem">'+nama_list+'<span class="ml-3"><input type="checkbox" name="status[]" value="'+data['id_list']+'" class="list" ></input></span><span class="ml-3"><button onclick="delete_list('+data['id_list']+')">X</button></span></li>');
                        $('#nama_list'+String(id_activity)).val('');
                      }
								}
							});
	}
  function add_list(id){
    console.log(id);
      $.ajax({
                type:"POST",
                data: {id:id},
                url: "<?php echo base_url('index.php/project/c_activity/add_list'); ?>",
                success: function(data){
                  $('#board_form_activity').html(data);
                }
              });
    }
  function edit_list(id){

    //var aksi = 'edit';
    console.log(id);
    $.ajax({
            type:"POST",
            data : { id:id },
            url: "<?php echo base_url('index.php/project/c_activity/view_list'); ?>",
            success: function(data){
              $('#board_form_activity').html(data);
            }
      });

  }


  function delete_activity(id) {
      if (confirm("Are you sure?")) {
        console.log(id);
            $.ajax({
          type:"POST",
          dataType : "JSON",
          data : {id:id},
          url: "<?php echo base_url('index.php/project/c_activity/delete_activity'); ?>",
          success: function(){
          }
        }); location.reload();
         }
    }
  function activeInput(id_activity){
    $('input.form-control.activity'+String(id_activity)).css('background','#fff');
    $('input.form-control.activity'+String(id_activity)).css('border','1px solid gray');
    $('input.form-control.activity'+String(id_activity)).css('border-radius','4px ');
    $('.editactivity'+String(id_activity)).css('display','none');
    $('.saveactivity'+String(id_activity)).css('display','block');
  }
  function saveInput(id){

    nama = $('#nama_activity_update'+String(id)).val();
  //  console.log(nama_Activity);

    $.ajax({
            type:"POST",
            dataType : "JSON",
            data : { id:id, nama:nama },
            url: "<?php echo base_url('index.php/project/c_activity/update_Nactivity'); ?>",
            success: function(data){
              //$('#nama_activity_update'+String(id_activity)).val(nama_Activity);
              $('input.form-control.activity'+String(id)).css('background','#f8f9fa');
              $('input.form-control.activity'+String(id)).css('border','none');
              $('input.form-control.activity'+String(id)).css('border-radius','0 ');
              $('.editactivity'+String(id)).css('display','block ');
              $('.saveactivity'+String(id)).css('display','none');
            }
      });


  }

</script>
