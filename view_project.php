<?php
include 'db_connect.php';
$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM risk_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM risk_list where project_id = {$id} and action = 1")->num_rows;
$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog,2) : $prog;
$prod = $conn->query("SELECT * FROM risk_mitigation where project_id = {$id}")->num_rows;
if($status == 0 && strtotime(date('Y-m-d')) >= strtotime($start_date)):
if($prod  > 0  || $cprog > 0)
  $status = 2;
else
  $status = 1;
elseif($status == 0 && strtotime(date('Y-m-d')) > strtotime($end_date)):
$status = 4;
endif;
$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>
<div class="col-lg-12">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
								<dt><b class="border-bottom border-primary">Organizational Units</b></dt>
								<dd><?php echo ucwords($name) ?></dd>
								<dt><b class="border-bottom border-primary">Unit</b></dt>
								<dd><?php echo html_entity_decode($unit) ?></dd>
								<dt><b class="border-bottom border-primary">Activity</b></dt>
								<dd><?php echo html_entity_decode($activity) ?></dd>
								<dt><b class="border-bottom border-primary">Activity Goal</b></dt>
								<dd><?php echo html_entity_decode($activity_goal) ?></dd>
								<dt><b class="border-bottom border-primary">Activity Description</b></dt>
								<dd><?php echo html_entity_decode($description) ?></dd>
							</dl>
						</div>
						<div class="col-md-6">
							<dl>
								<dt><b class="border-bottom border-primary">Start Date</b></dt>
								<dd><?php echo date("F d, Y",strtotime($start_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">End Date</b></dt>
								<dd><?php echo date("F d, Y",strtotime($end_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Project Status</b></dt>
								<dd>
									<?php
									  if($stat[$status] =='Pending'){
									  	echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Started'){
									  	echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='On-Progress'){
									  	echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='On-Hold'){
									  	echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Over Due'){
									  	echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Done'){
									  	echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									  }
									?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Project Manager</b></dt>
								<dd>
									<?php if(isset($manager['id'])) : ?>
									<div class="d-flex align-items-center mt-1">
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
										<b><?php echo ucwords($manager['name']) ?></b>
									</div>
									<?php else: ?>
										<small><i>Manager Deleted from Database</i></small>
									<?php endif; ?>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Team Member/s:</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php 
						if(!empty($user_ids)):
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while($row=$members->fetch_assoc()):
						?>
								<li>
			                        <img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
			                        <a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
			                        <!-- <span class="users-list-date">Today</span> -->
		                    	</li>
						<?php 
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Risk List:</b></span>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_risk"><i class="fa fa-plus"></i> New Risk</button>
					</div>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
					<table class="table table-condensed m-0 table-hover">
						<colgroup>
							<col width="5%">
							<col width="25%">
							<col width="10%">
							<col width="25%">
							<col width="25%">
							<col width="25%">
							<col width="25%">
							<col width="15%">
							<col width="15%">
							<col width="30%">
							<col width="5%">
						</colgroup>
						<thead>
							<th>#</th>
							<th>Risk Description</th>
							<th>Risk Owner</th>
							<th>Risk Cause</th>
							<th>Risk Impact</th>
							<th>Existing Controls</th>
							<th>Effective/Less Effective</th>
							<th>Risk Status</th>
							<th>Manager Action</th>
							<th>Notes</th>
							<th></th>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							$risks = $conn->query("SELECT * FROM risk_list where project_id = {$id} order by risk asc");
							while($row=$risks->fetch_assoc()):
								$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
								unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
							?>
								<tr>
			                        <td class="text-center"><?php echo $i++ ?></td>
									<td class=""><b><?php echo ucwords($row['risk']) ?></b></td>
			                        <td class=""><b><?php echo ucwords($row['owner']) ?></b></td>
									<td class=""><b><?php echo ucwords($row['cause']) ?></b></td>
									<td class=""><b><?php echo ucwords($row['impact']) ?></b></td>
									<td class=""><b><?php echo ucwords($row['controls']) ?></b></td>
									<td>
			                        	<?php 
			                        	if($row['effective'] == 1){
									  		echo "<span class='badge badge-light'>Effective</span>";
			                        	}elseif($row['effective'] == 2){
									  		echo "<span class='badge badge-dark'>Less Effective</span>";
			                        	}
			                        	?>
			                        </td>
			                        <td>
			                        	<?php 
			                        	if($row['status'] == 1){
									  		echo "<span class='badge badge-pill badge-primary'>Very Low</span>";
			                        	}elseif($row['status'] == 2){
									  		echo "<span class='badge badge-pill badge-success'>Low</span>";
			                        	}elseif($row['status'] == 3){
									  		echo "<span class='badge badge-pill badge-secondary'>Medium</span>";
			                        	}elseif($row['status'] == 4){
											echo "<span class='badge badge-pill badge-warning'>High</span>";
									  	}elseif($row['status'] == 5){
											echo "<span class='badge badge-pill badge-danger'>Very High</span>";
									  	}
			                        	?>
										
			                        </td>
									<td>
										<?php 
										if($row['action'] == 0){
											echo "<span class='badge badge-pill badge-light'>Pending</span>";
										}elseif($row['action'] == 1){
											echo "<span class='badge badge-pill badge-primary'>Desirable</span>";
										}elseif($row['action'] == 2){
											echo "<span class='badge badge-pill badge-success'>Acceptable</span>";
										}elseif($row['action'] == 3){
											echo "<span class='badge badge-pill badge-secondary'>Undesirable</span>";
										}elseif($row['action'] == 4){
											echo "<span class='badge badge-pill badge-warning'>Unacceptable</span>";
										}elseif($row['action'] == 5){
											echo "<span class='badge badge-pill badge-danger'>Catastrophic</span>";
										}
										?>
									</td>
									<td>
										<?php echo html_entity_decode($row['notes']) ?>
									</td>
									<td>
									</td>
							
			                        <td class="text-center">
										<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
					                    </button>
					                    <div class="dropdown-menu" style="">
										  <?php if($_SESSION['login_type'] != 2 ): ?>
					                      <a class="dropdown-item view_risk" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-risk="<?php echo $row['risk'] ?>">View</a>
					                      <div class="dropdown-divider"></div>
					                      <a class="dropdown-item edit_risk" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-risk="<?php echo $row['risk'] ?>">Edit</a>
					                      <div class="dropdown-divider"></div>
										  <a class="dropdown-item delete_risk" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
										  <?php endif; ?>
										  <?php if($_SESSION['login_type'] == 2 ): ?>
										  <a class="dropdown-item review_risk" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-risk="<?php echo $row['risk'] ?>">Review</a>
										  <?php endif; ?>
					                    </div>
									</td>
		                    	</tr>
							<?php 
							endwhile;
							?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b>Budgeting Documents</b>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_budgeting"><i class="fa fa-plus"></i> New Budget</button>
					</div>
				</div>
				<div class="card-body">
					<?php 
					$budget = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar,t.risk FROM risk_mitigation p inner join users u on u.id = p.user_id inner join risk_list t on t.id = p.risk_id where p.project_id = $id order by unix_timestamp(p.date_created) desc ");
					while($row = $budget->fetch_assoc()):
					?>
						<div class="post">

							<div class="user-block">
							<?php if($_SESSION['login_id'] == $row['user_id']): ?>
							<span class="btn-group dropleft float-right">
								<span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
								<i class="fa fa-ellipsis-v"></i>
								</span>
								<div class="dropdown-menu">
								<a class="dropdown-item manage_mitigation" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-risk="<?php echo $row['risk'] ?>">Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_mitigation" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"> Delete</a>
								</div>
							</span>
							<?php endif; ?>
							<img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
							<span class="username">
								<a href="#"><?php echo ucwords($row['uname']) ?>[ <?php echo ucwords($row['risk']) ?> ]</a>
							</span>
							<span class="description">
								<span class="fa fa-calendar-day"></span>
								<span><b><?php echo date('M d, Y',strtotime($row['date_created'])) ?></b></span>
							</span>
							

							
							</div>
							<!-- /.user-block -->
							<div>
							<?php echo html_entity_decode($row['subject']) ?>
							</div>

							<div class="sub-total"> Total Budget :
							<span>Rp
							<?php echo html_entity_decode($row['subTotal']) ?>
							</div>

							<p>
							<!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a> -->
							</p>
	                    </div>
	                    <div class="post clearfix"></div>
                    <?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.users-list>li img {
	    border-radius: 50%;
	    height: 67px;
	    width: 67px;
	    object-fit: cover;
	}
	.users-list>li {
		width: 33.33% !important
	}
	.truncate {
		-webkit-line-clamp:1 !important;
	}
</style>
<script>
	$('#new_risk').click(function(){
		uni_modal("New Risk For <?php echo ucwords($name) ?>","manage_risk.php?pid=<?php echo $id ?>","mid-large")
	})
	$('.edit_risk').click(function(){
		uni_modal("Edit Risk: "+$(this).attr('data-risk'),"manage_risk.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.review_risk').click(function(){
		uni_modal("Review Risk: "+$(this).attr('data-risk'),"review_risk.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.view_risk').click(function(){
		uni_modal("Risk Details","view_risk.php?id="+$(this).attr('data-id'),"mid-large")
	})
	$('#new_budgeting').click(function(){
		uni_modal("<i class='fa fa-plus'></i> New Budget","manage_budget.php?pid=<?php echo $id ?>",'large')
	})
	$('.manage_mitigation').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Edit Budget","edit_budget.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
	})
	$('.delete_mitigation').click(function(){
	_conf("Are you sure to delete this budget?","delete_mitigation",[$(this).attr('data-id')])
	})
	$('.delete_risk').click(function(){
	_conf("Are you sure to delete this risk?","delete_risk",[$(this).attr('data-id')])
	})
	function delete_mitigation($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_mitigation',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}else {
					alert_toast("Failed to delete data", 'error');
				}
			}
		})
	}
	function delete_risk($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_risk',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>