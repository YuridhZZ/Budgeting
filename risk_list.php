<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Organizational Units</th>
						<th>Risk</th>
						<th>Project Started</th>
						<th>Project Due Date</th>
						<th>Risk Status</th>
						<th>Manager Action</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if($_SESSION['login_type'] == 2){
						$where = " where p.manager_id = '{$_SESSION['login_id']}' ";
					}elseif($_SESSION['login_type'] == 3){
						$where = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
					}
					
					$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
					$qry = $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM risk_list t inner join project_list p on p.id = t.project_id $where order by p.name asc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$tprog = $conn->query("SELECT * FROM risk_list where project_id = {$row['pid']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM risk_list where project_id = {$row['pid']} and action = 1")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM risk_mitigation where project_id = {$row['pid']}")->num_rows;


					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td>
							<p><b><?php echo ucwords($row['pname']) ?></b></p>
						</td>
						<td>
							<p><b><?php echo ucwords($row['risk']) ?></b></p>
						</td>
						<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
						<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
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
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                    </button>
			                    <div class="dropdown-menu" style="">
			                      <a class="dropdown-item new_budget" data-pid = '<?php echo $row['pid'] ?>' data-tid = '<?php echo $row['id'] ?>'  data-risk = '<?php echo ucwords($row['risk']) ?>'  href="javascript:void(0)">Add Mitigation</a>
								  
								</div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.new_budget').click(function(){
		uni_modal("<i class='fa fa-plus'></i> New budget for: "+$(this).attr('data-risk'),"manage_budget.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
	})
	})
	function delete_project($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_project',
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