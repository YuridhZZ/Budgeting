<?php
ob_start();
date_default_timezone_set("Asia/Jakarta");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'save_project'){
	$save = $crud->save_project();
	if($save)
		echo $save;
}
if($action == 'delete_project'){
	$save = $crud->delete_project();
	if($save)
		echo $save;
}
if($action == 'save_risk'){
	$save = $crud->save_risk();
	if($save)
		echo $save;
}
if($action == 'delete_risk'){
	$save = $crud->delete_risk();
	if($save)
		echo $save;
}
if($action == 'save_mitigation'){
	$save = $crud->save_mitigation();
	if($save)
		echo $save;
}
if($action == 'delete_mitigation') {
    $delete = $crud->delete_mitigation();
    if ($delete)
        echo $delete;
}

if($action == 'save_budget'){
	$save = $crud->save_budget();
	if($save)
		echo $save;
}
if($action == 'delete_budget'){
	$save = $crud->delete_budget();
	if($save)
		echo $save;
}
ob_end_flush();
?>
