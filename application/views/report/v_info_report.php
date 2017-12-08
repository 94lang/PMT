<?php $this->load->view('asset'); ?>
<title>Report | Solusi 247 </title>
<?php $this->load->view('navigasi'); ?>
<div class="container-fluid dashboard px-4">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<!-- Latest Report -->
				<div class="box" >
						<div class="box-header">
							<?php
									foreach ($data_project as $u2) {
											$id_project 	= $u2->id_project;
											$nama_project 	= $u2->nama_project;
											$start_date 	= $u2->start_date;
											$finish_date 	= $u2->finish_date;
											$status_project	= $u2->status_on_report;
											$progress 		= $u2->progress_on_report;
											$id_report 	= $u2->id_report;
											$date_report= $u2->date_report;
											$keterangan	= $u2->keterangan;
											$nama_user 	= $u2->nama_user;

										}
								?>
							<h3 class="box-title"><?php echo $nama_project ?></h3>
						</div>
						<div class="box-body" style="min-height:450px">
							<?php
							echo '
								<p>'.$nama_user.' ('.$date_report.')</p>
								<br/>
								<table class="table table-condensed ">
									<tr>
										<td>Waktu Pengerjaan</td>
										<td> '.$start_date.' sampai '.$finish_date.'</td>
									</tr>
									<tr>
										<td>Status / Progress</td>
										<td>'.$status_project.' / '.$progress.'%</td>
									</tr>
									<tr>
										<td>Keterangan</td>
										<td>'.$keterangan.'</td>
									</tr>
									<tr>
										<td>Detail</td>
										<td>';if($n_task >= 1 ){
													foreach ($task as $uvb) {
															$id_task = $uvb->id_task;
															$nama_task = $uvb->nama_task;
															echo $nama_task.' (on progress)<br/><ul>';
																if($n_activity >= 1 ){
																		foreach ($activity as $uvb) {
																			$id_activity = $uvb->id_activity;
																			$nama_activity = $uvb->nama_activity;
																			$from_id_task = $uvb->from_id_task;

																			if($from_id_task == $id_task ){
																				echo '<li>'.$nama_activity.' (on progress)<br/><ol>';

																				foreach ($list as $uvb) {
																					$id_list = $uvb->id_list;
																					$list_activity = $uvb->list_activity;
																					$from_id_activity = $uvb->from_id_activity;

																					if($from_id_activity == $id_activity ){
																						echo '<li>'.$list_activity.' (done)<br/></li>';
																					}
																				}
																				echo'</ol></li>';
																			}
																		}
																} echo'</ul>';
													}
												}
								echo'
									</p>
								';echo'</td>
									</tr>
								</table>
								';
							?>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</body>
<?php $this->load->view('function'); ?>
