<?php //$this->load->view('plugin'); ?>
<?php

if($aksi == 'edit'){
		foreach ($data_task as $u2) {
			$id_task 		= $u2->id_task;
			$nama_task 		= $u2->nama_task;
			$status_task 	= $u2->status_task;
			$id_project 	= $u2->from_id_project;

			$member = $member;
			$task_member = $member_on;
		}
	}

	else if($aksi == 'add new'){
			$id_project 	= $id_project;
			$id_task 		= null;
			$nama_task 		= null;
			$status_task 	= null;

			$member = $member;
	}

echo '
	<form class="form-horizontal" name="form_task" id="form_task" >
	  <div class="form-group">
	    <label class="control-label col-sm-12" for="email">Task Title</label>
	    <div class="col-sm-12">
	      	<input type="hidden" class="form-control" id="aksi" name="aksi" value="'.$aksi.'" >
	      	<input type="hidden" class="form-control" id="id_project" name="id_project" value="'.$id_project.'" >
	      	<input type="hidden" class="form-control" id="id_task" name="id_task" value="'.$id_task.'" >
	      <input type="text" class="form-control" id="nama_task" name="nama_task" value="'.$nama_task.'" >
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="control-label col-sm-12" for="email">Status</label>
	    <div class="col-sm-12">
	      <input type="text" class="form-control" id="status_task" name="status_task" value="'.$status_task.'"  >
	    </div>
	  </div>

	  <div class="form-group">
	  	    <div class="col-sm-12">
	    		    Member Not On Task
	    		    	<table class="table table-md table-responsive">
	    		';

	    			foreach ($member as $u3) {
	    				echo '	<tr>
	    							<td>
	    								'. $u3->nama_user.'
	    							</td><td>
	    								<input type="checkbox" name="members[]" value="'.$u3->id_user.'" class="member"><span class="ml-2">Add</span></input>
	    							</td>
	    						</tr>
	    					';
	    			}

	    	echo'		</table>
	    			</div>
	    			<div class="col-sm-12">
	    		';

	    if($aksi == 'edit'){
	    	echo '

	    			    Member Of This Task
	    			    	<table class="table table-md table-responsive">
	    			';

	    				foreach ($task_member as $u4) {
	    					echo '	<tr>
	    								<td>
	    									'. $u4->nama_user.'
	    								</td><td>
	    									<input type="checkbox" name="drops[]" value="'.$u4->id_user.'" class="member"><span class="ml-2">Hapus</span></input>
	    								</td>
	    							</tr>
	    						';
	    				}

	    		echo'		</table>

	    			';
	    	}

	    echo'
	    			</div>
	  </div>

	  <div class="form-group">
						<h4 id="message"></h4>
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default">Submit</button>
	    </div>
	  </div>
	</form>
	';
?>
<script>
	$(document).ready(function(){
	    $("#form_task").submit(function(e){
	    	e.preventDefault();

	    	aksi = $('input[name=aksi]').val();
	    	//id_project = $('input[name=id_project]').val();

	    	if(aksi == 'add new'){
	    		url = "<?php echo base_url('index.php/project/c_task/insert_task'); ?>"
	    	}else if(aksi == 'edit'){
	    		url = "<?php echo base_url('index.php/project/c_task/update_task'); ?>"
	    	}

	    	//console.log(id_project,aksi);

	        $.ajax({
						type:"POST",
						dataType : "JSON",
						data : $('#form_task').serialize(),
						url: url,
						success: function(data){

							$('#message').html(data['mssg']);
							window.setTimeout(function(){location.reload()},2000)
						}
			});
	    });
});
</script>
