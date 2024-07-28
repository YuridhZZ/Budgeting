<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."'  ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 2;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function update_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table','password')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(!empty($password))
			$data .= " ,password=md5('$password') ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_system_settings(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	function save_project(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(isset($user_ids)){
			$data .= ", user_ids='".implode(',',$user_ids)."' ";
		}
		// echo $data;exit;
		if(empty($id)){
			$save = $this->db->query("INSERT INTO project_list set $data");
		}else{
			$save = $this->db->query("UPDATE project_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_project(){
		extract($_POST);
	
		// Get mitigation IDs associated with the project
		$getMitigationIds = $this->db->query("SELECT id FROM risk_mitigation WHERE project_id = $id");
	
		// Delete project
		$deleteProject = $this->db->query("DELETE FROM project_list WHERE id = $id");
	
		if($deleteProject){
			// Loop through each mitigation and delete related records
			while ($mitigationRow = $getMitigationIds->fetch_assoc()) {
				$mitigation_id = $mitigationRow['id'];
	
				// Delete related budgets
				$deleteBudget = $this->db->query("DELETE FROM budget WHERE mitigation_id = $mitigation_id");
			}
	
			// Delete related risks
			$deleteRisks = $this->db->query("DELETE FROM risk_list WHERE project_id = $id");
	
			// Delete related mitigations
			$deleteMitigations = $this->db->query("DELETE FROM risk_mitigation WHERE project_id = $id");
	
			// Additional cleanup or actions can be added here
	
			return 1;
		} else {
			return 0; // Return 0 if the delete operation for the project fails
		}
	}	
	function save_risk(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO risk_list set $data");
		}else{
			$save = $this->db->query("UPDATE risk_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_risk(){
		extract($_POST);
		
		// Get mitigation IDs associated with the risk
		$getMitigationIds = $this->db->query("SELECT id FROM risk_mitigation WHERE risk_id = $id");
		
		// Delete risk
		$deleteRisk = $this->db->query("DELETE FROM risk_list WHERE id = $id");
		
		if($deleteRisk){
			// Loop through each mitigation and delete related records
			while ($mitigationRow = $getMitigationIds->fetch_assoc()) {
				$mitigation_id = $mitigationRow['id'];
	
				// Delete related budgets
				$deleteBudget = $this->db->query("DELETE FROM budget WHERE mitigation_id = $mitigation_id");
			}
	
			// Delete related mitigations
			$deleteMitigations = $this->db->query("DELETE FROM risk_mitigation WHERE risk_id = $id");
	
			// Additional cleanup or actions can be added here
			
			return 1;
		} else {
			return 0; // Return 0 if the delete operation for the risk fails
		}
	}	
	function save_mitigation(){
		extract($_POST);
	
		// Check if the id parameter is present for an update operation
		if(!empty($id)) {
			// Update the risk_mitigation table
			$updateMitigationQuery = "UPDATE risk_mitigation SET project_id='$project_id', risk_id='$risk_id', subject='$subject', subTotal='$subTotal' WHERE id='$id'";
			
			if(!$this->db->query($updateMitigationQuery)) {
				return 0; 
			}
	
			$deleteQuery = "DELETE FROM budget WHERE mitigation_id='$id'";
			$this->db->query($deleteQuery);
		}
		else {
			// Insert a new record into the risk_mitigation table to get its auto-generated id
			$insertMitigationQuery = "INSERT INTO risk_mitigation (project_id, risk_id, subject, user_id, subTotal)
									VALUES ('$project_id', '$risk_id', '$subject', '{$_SESSION['login_id']}', '$subTotal')";
	
			if ($this->db->query($insertMitigationQuery)) {
				$id = $this->db->insert_id; // Get the auto-generated id
			}
			else {
				return 0; // Return 0 to indicate an error in the insert operation
			}
		}
	
		// Check if mitigation_id is provided
		if (is_array($code) && is_array($detail) && is_array($quantity) && is_array($units) && is_array($price) && is_array($total_cost)) {
			$rowCount = count($code);
	
			// Check if arrays have the same number of elements
			if ($rowCount === count($detail) && $rowCount === count($quantity) && $rowCount === count($units) && $rowCount === count($price) && $rowCount === count($total_cost)) {
				$successCount = 0;
	
				for ($i = 0; $i < $rowCount; $i++) {
					$codeValue = $this->db->real_escape_string($code[$i]);
					$detailValue = $this->db->real_escape_string($detail[$i]);
					$quantityValue = (int)$quantity[$i];
					$unitsValue = $this->db->real_escape_string($units[$i]); // Add this line for the units column
					$priceValue = (float)$price[$i];
					$totalCostValue = (float)$total_cost[$i];
	
					// Construct the INSERT Description for each row with the retrieved mitigation_id
					$insertQuery = "INSERT INTO budget (mitigation_id, code, detail, quantity, units, price, total_cost)
									VALUES ('$id', '$codeValue', '$detailValue', $quantityValue, '$unitsValue', $priceValue, $totalCostValue)";
	
					if ($this->db->query($insertQuery)) {
						$successCount++;
					}
				}
				return 1; // Return 1 to indicate success
			}
		}
	
		return 0; // Return 0 for any other error case
	}
	
	
	function delete_mitigation(){
		extract($_POST);
		
		// Delete the mitigation record
		$deleteMitigation = $this->db->query("DELETE FROM risk_mitigation WHERE id = $id");
	
		if ($deleteMitigation) {
			// Retrieve the ID of the deleted mitigation
			$mitigation_id = $id;
	
			// Delete related records from the budget table
			$deleteBudget = $this->db->query("DELETE FROM budget WHERE mitigation_id = $mitigation_id");
	
			return 1;
		} else {
			// Handle the case where the delete operation failed
			echo "Delete operation for mitigation failed: " . $this->db->error;
			return 0;
		}
	}
}