<?php $this->load->view('asset'); ?>
	<title>Manage Project | Solusi247</title>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
<?php $this->load->view('navigasi');?>
		<div class="container-fluid dashboard px-4">
			<div class="row">
				<div class="col-md-3">
					<div class="row mb-3">
						<!-- Check for access type -->
						<?php
						if($this->session->userdata('akses') == 'admin'){
								echo'<a href="'.base_url().'index.php/add/project" class="btn btn-info btn-block text-white">Add New Project</a>';
							}
						?>
					</div>
					<div class="row">
						<!-- Project Summary -->
						<div class="box">
						  	<div class="box-header">
						    	<h3 class="box-title">Project Summary</h3>
						  	</div>
								<div class="box-body">
						  		<table class="table table-condensed">
						  		  <tr>
						  		    <td>1.</td>
						  		    <td>Total Project</td>
						  		    <td><span class="badge badge-primary"><?php echo $all_p;?></span></td>
						  		  </tr>
						  		  <tr>
						  		    <td>2.</td>
						  		    <td>On Planning</td>
						  		    <td><span class="badge badge-info"><?php echo $planning_p;?></span></td>
						  		  </tr>
						  		  <tr>
						  		    <td>3.</td>
						  		    <td>On Progress</td>
						  		    <td><span class="badge badge-success"><?php echo $progresh_p;?></span></td>
						  		  </tr>
						  		  <tr>
						  		    <td>4.</td>
						  		    <td>Has Done</td>
						  		    <td><span class="badge badge-danger"><?php echo $done_p;?></span></td>
						  		  </tr>
						  		</table>
						  	</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<!-- Project List -->
					<div class="box">
					  <div class="box-header">
					    <h3 class="box-title">Project List</h3>
					  </div>
					  <div class="box-body">
					    <table id="example" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
					      <thead>
					      <tr>
					        <th>Project Name</th>
					        <th>Start Date</th>
					        <th>End Date</th>
					        <th>Project Status</th>
					        <th>Progress</th>
					        <th>Option</th>
					      </tr>
					      </thead>
					      <tbody>
					      <?php
					      	foreach ($all_project as $u) {
					      		$id_project 	= $u->id_project;
					      		$nama_project 	= $u->nama_project;
					      		$start_date 	= $u->start_date;
					      		$finish_date 	= $u->finish_date;
					      		$status_project	= $u->status_project;
					      		$progress 		= $u->progress;
					      		echo '
					      			<tr>
					      				<td>
					      				  <a  href="'.base_url().'index.php/view/project/'.$id_project.'" >
					      				            '.$nama_project.'
					      				  </a>
					      				</td>
					      				<td>'.$start_date.'</td>
					      				<td>'.$finish_date.'</td>
					      				<td>'.$status_project.'</td>
					      				<td>'.$progress.'%</td>
					      				<td class="text-center">
					      			';
					      	if($this->session->userdata('akses') == 'admin'){
					      		echo'
					      				<ul class="list-unstyled d-inline-flex">
					      					<li style="background-color:#f39c12;">
					      						<a href="'.base_url().'index.php/edit/project/'.$id_project.'"><i class="material-icons">mode_edit</i></a>
					      					</li>
					      					<li style="background-color:#e74c3c;">
					      						<a href="#"  onclick="delete_project('.$id_project.')"><i class="material-icons">delete</i></a>
					      					</li>
					      				</ul>
					      			';
					      	}
					      		echo'
					      				</td>
					      			</tr>
					      		';
					      	}
					      ?>
					      </tbody>
					    </table>

					    <div id="board">
					    </div>
					    <script type="text/javascript">
					    	function close_fom(){
					    		$('#board').empty();
					    	}
					    	function add_project(){
					    			$.ajax({
					    								type:"POST",
					    								url: "<?php echo base_url('index.php/project/c_project/add_project'); ?>",
					    								success: function(data){
					    									$('#board').html(data);
					    								}
					    							});
					    		}
					    	function view_project(id){
					    		$('#board').empty();
					    		var aksi = 'view';
					    		$.ajax({
					    						type:"POST",
					    						data : {id:id,aksi:aksi},
					    						url: "<?php echo base_url('index.php/project/c_project/view_project/id'); ?>",
					    						success: function(data){
					    							$('#board').html(data);
					    						}
					    			});


					    	}

					    	function edit_project(id){
					    		$('#board').empty();

					    		var aksi = 'edit';
					    		console.log(id,aksi);
					    		$.ajax({
					    						type:"POST",
					    						data : { id:id , aksi:aksi },
					    						url: "<?php echo base_url('index.php/project/c_project/view_project'); ?>",
					    						success: function(data){
					    							$('#board').html(data);
					    						}
					    			});

					    	}
					    	function delete_project(id) {
					    	    if (confirm("Are you sure?")) {
					    	    	console.log(id);
					    		        $.ajax({
					    					type:"POST",
					    					dataType : "JSON",
					    					data : {id:id},
					    					url: "<?php echo base_url('index.php/project/c_project/delete_project'); ?>",
					    					success: function(){

					    					}
					    				}); location.reload();
					    		}
					    	}
					    </script>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<?php $this->load->view('function'); ?>
<script>
 $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
</html>
