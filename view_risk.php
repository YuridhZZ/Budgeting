<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM risk_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Risk Description</b></dt>
		<dd><?php echo ucwords($risk) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Risk Owner</b></dt>
		<dd><?php echo ucwords($owner) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Risk Cause</b></dt>
		<dd><?php echo ucwords($cause) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Risk Impact</b></dt>
		<dd><?php echo ucwords($impact) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Existing Controls</b></dt>
		<dd><?php echo ucwords($controls) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Effective/Less Effectiv</b></dt>
		<dd>
			<?php 
        	if($effective == 1){
		  		echo "<span class='badge badge-light'>Effective</span>";
        	}elseif($effective == 2){
		  		echo "<span class='badge badge-dark'>Less Effective</span>";
			}
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Risk Status</b></dt>
		<dd>
			<?php 
        	if($status == 1){
		  		echo "<span class='badge badge-pill badge-primary'>Very Low</span>";
        	}elseif($status == 2){
		  		echo "<span class='badge badge-pill badge-success'>Low</span>";
        	}elseif($status == 3){
		  		echo "<span class='badge badge-pill badge-secondary'>Medium</span>";
        	}
			elseif($status == 4){
				echo "<span class='badge badge-pill badge-warning'>High</span>";
		  	}elseif($status == 5){
				echo "<span class='badge badge-pill badge-danger'>Very High</span>";
		  	}
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Action</b></dt>
		<dd>
			<?php 
			if($action == 0){
				echo "<span class='badge badge-pill badge-light'>Pending</span>";
			}elseif($action == 1){
				echo "<span class='badge badge-pill badge-primary'>Desirable</span>";
			}elseif($action == 2){
				echo "<span class='badge badge-pill badge-success'>Acceptable</span>";
			}elseif($action == 3){
				echo "<span class='badge badge-pill badge-secondary'>Undesirable</span>";
			}elseif($action == 4){
				echo "<span class='badge badge-pill badge-warning'>Unacceptable</span>";
			}elseif($action == 5){
				echo "<span class='badge badge-pill badge-danger'>Catastrophic</span>";
			}
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Notes</b></dt>
		<dd>
		<?php echo html_entity_decode($notes) ?>
		</dd>
	</dl>
</div>