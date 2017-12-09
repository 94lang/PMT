<?php
		foreach ($data_project as $u2) {
			$id_project 	= $u2->id_project;
			$nama_project 	= $u2->nama_project;
			$start_date 	= $u2->start_date;
			$finish_date 	= $u2->finish_date;
			$status_project	= $u2->status_project;
			$progress 		= $u2->progress;
		}
	  if($actor == 'team member'){
		foreach ($roles as $ux) {
				$role = $ux->role;
		}
	  }else $role = 'none';
echo'<br>
	';
?>
<?php $this->load->view('asset'); ?>
	<title>Manage User | Solusi247</title>
<?php $this->load->view('navigasi'); ?>
		<div class="container-fluid dashboard px-4">
			<div class="row">
				<div class="col-md-12">
					<!-- Box for project container -->
					<div class="box" style="box-shadow: 0 0.5px 0.5px rgba(0, 0, 0, 0.1)">
						<div class="box-header">
							<ul class="list-unstyled d-inline-flex">
								<li class="mr-3"><span><a href="<?php echo base_url('index.php/project');?>" ><i style="position: relative;" class="material-icons">arrow_back</i></a></span>
								</li>
								<li><h3 class="box-title">Project List</h3></li>
							</ul>
							<span class="float-right">
								<ul class="list-unstyled d-inline-flex">
									<li class="ml-3 mr-3"><h3 class="box-title"><?php echo $nama_project ?></h3></li>
									<li class="ml-3 mr-3">Start Date <span class="ml-2 badge badge-primary"><?php echo $start_date ?></span></li>
									<li class="ml-3 mr-3">End Date <span class="ml-2 badge badge-info"><?php echo $finish_date ?></span></li>
									<li class="ml-3 mr-3">Status <span class="ml-2 badge badge-success"><?php echo $status_project ?></span></li>
								</ul>
							</span>
							<br>
							<hr>
							<p class="lead" style="font-size:1rem">You are <?php echo $actor ?> in this project as <span class="badge badge-warning" style="color: #fff9f9;"><?php echo $role ?> </span>
								<span >
									<?php
									if($actor == 'team member'){
										foreach ($roles as $ux) {
											$role = $ux->role;
											echo'<button class="btn btn-danger btn-sm ml-3"  onclick="create_report('.$id_project.')"><small>Create Report</small></button>  <h4 id="message"></h4>';
										}
									}else $role = 'none';
									?>
								</span>
							</p>
						</div>
							<!-- Content of project -->
							<div class="box-body" style="padding-top:0" id="callHeights">
					  		<div class="row">
									<div class="col-md-12">
										<div id="taskhidden">
											<div class="box-header">
												<span><a href="#" onclick="hideBox()" ><i style="position: relative;" class="material-icons">close</i></a></span>
											</div>
											<div class="box-body">
												<div class="board" ></div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
	                  <div class="box" style="border: none; min-height:90px;">
	                    <div class="box-header">
	                      <h3 class="box-title">Task List</h3>
	                    </div>
	                    <?php
	                      foreach ($task as $u4) {
	                      $id_task 		= $u4->id_task;
												$nama_task 		= $u4->nama_task;
												$status_task 		= $u4->status_task ;
	                      echo'
	                      <div class="col-md-12">
	                        <div class="box" style="box-shadow: 2px 1px 8px 1px rgba(0, 0, 0, 0.1);" >
	                          <div class="box-body" style="padding: 15px 20px 15px !important;">
	                            <p href="#" class="box-title" style="display:inline-flex; cursor:pointer;" onclick="view_task('.$id_task.','.$id_project.')">
	                            '.$nama_task.'
	                            <span>
	                              <ul class="list-unstyled d-inline-flex float-right">
	                                <li style="margin:0px 5px; "><span class="badge badge-danger">

																	';
																		foreach ($task_user as $u40) { if($u40->from_id_task == $id_task){echo $u40->nama_user.' / ';} }

												echo'				</span></li>
	                                <li style="margin:0px 5px; "><span class="badge badge-primary">'.$status_task.'</span></li>
	                              </ul>
	                            </span>
	                            </p>
	                          </div>
	                          </div>
	                      </div>
	                      ';
	                    }
	                    ?>
	                  </div>
	                </div>
									<div class="col-md-6">
										<?php
										if($role == 'project leader'){
											echo'<div class="box"  style="border:none; min-height:90px;box-shadow: 0 0.5px 0.5px rgba(0, 0, 0, 0.1);">
											<div class="box-header">
											<h3 class="box-title">Add New Task</h3>
											</div>
											<div class="box-body" style="padding:0 20px 20px 20px" id="form_task_board">

											</div>
											</div>';
										}
										?>
									</div>
									</div>
					  		</div>
					  	</div>
							<div class="box-footer">
	              <div class="row">
	                <div class="col-md-12 mt-3">
	                    <span class="lead" style="font-size:1rem">Members of this project: <?php
	                    foreach ($member as $u3) {
	                      $id_user 		= $u3->id_user;
	                      $nama_user 		= $u3->nama_user;
	                      echo '<p  class="d-inline-flex ml-3 lead" style="font-size:1rem">['.$nama_user.']</p>';
	                    }
	                    ?></span>
	                </div>
	              </div>
	            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

<script>
// Call fromt add task when document load
$(document).ready(function(){
	var role = "<?php echo $role?>";
	if(role == "project leader"){
			var id = "<?php echo $id_project?>";
			$.ajax({
							type:"POST",
							data: {id:id},
							url: "<?php echo base_url('index.php/project/c_task/add_task'); ?>",
							success: function(data){
								$('#form_task_board').html(data);
							}
						});
	}
});
</script>
<script type="text/javascript">
var callHeight
$(document).ready(function(){
	 callHeight= document.getElementById("callHeights").offsetHeight;

});
	function close_fom(){
		$('#board').empty();
	}
	function add_task(id){
		console.log(id);
			$.ajax({
								type:"POST",
								data: {id:id},
								url: "<?php echo base_url('index.php/project/c_task/add_task'); ?>",
								success: function(data){
									$('#task').html(data);
								}
							});
		}

	function view_task(id,id_p){
		$('.board').empty();
		document.getElementById("taskhidden").style.width = "100%";
		document.getElementById("taskhidden").style.height = "auto";
		document.getElementById("taskhidden").style.minHeight = callHeight;
		document.getElementById("taskhidden").style.padding = "20px";
		document.getElementById('callHeights').style.paddingLeft= "15px";
		document.getElementById('callHeights').style.paddingRight= "15px";
		var role = "<?php echo $role; ?>";
		var end_date = "<?php echo $finish_date ?>";
		var aksi = 'view';
		console.log(id,aksi);
		$.ajax({
						type:"POST",
						data : { id:id , id_p:id_p, aksi:aksi, role:role, end_date:end_date },
						url: "<?php echo base_url('index.php/project/c_task/view_task'); ?>",
						success: function(data){
							$('.board').html(data);
						}
			});
	}

	function edit_task(id,id_p){
		$('#board2').empty();

		var aksi = 'edit';
		console.log(id,aksi);
		$.ajax({
						type:"POST",
						data : { id:id , id_p:id_p, aksi:aksi },
						url: "<?php echo base_url('index.php/project/c_task/view_task'); ?>",
						success: function(data){
							$('.board').html(data);
						}
			});

	}

	function delete_task(id) {
	    if (confirm("Are you sure?")) {
	    	console.log(id);
		        $.ajax({
					type:"POST",
					dataType : "JSON",
					data : {id:id},
					url: "<?php echo base_url('index.php/project/c_task/delete_task'); ?>",
					success: function(){

					}
				}); location.reload();
		        // return false;
	   		}

		}
	function create_report(id) {
        $('#board').empty();

      var aksi = 'report';
      console.log(id,aksi);
      $.ajax({
              type:"POST",
              data : { id:id, aksi:aksi },
              url: "<?php echo base_url('index.php/report/c_report/requesto_create_report'); ?>",
              success: function(data){

                if(data == 'ok'){
                    document.location.href ="<?php echo base_url(''); ?>index.php/create/report/"+id ;
                }else {
									$('#message').html(data);
									window.setTimeout(function(){ $('#message').empty(); },3000)
								}
              }
        });
    }
		function hideBox() {
			document.getElementById("taskhidden").style.width = "0";
			document.getElementById("taskhidden").style.padding = "0";

		}
</script>

<?php $this->load->view('function'); ?>
</html>
