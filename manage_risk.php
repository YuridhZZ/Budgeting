<?php 
include 'db_connect.php';
session_start();
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM risk_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<style>
	/* Hide the select visually but keep it accessible */
.visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    margin: -1px;
    padding: 0;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}
</style>
<div class="container-fluid">
	<form action="" id="manage-risk">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Risk Description</label>
			<input type="text" class="form-control form-control-sm" name="risk" value="<?php echo isset($risk) ? $risk : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Risk Owner</label>
			<input type="text" class="form-control form-control-sm" name="owner" value="<?php echo isset($owner) ? $owner : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Risk Cause</label>
			<input type="text" class="form-control form-control-sm" name="cause" value="<?php echo isset($cause) ? $cause: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Risk Impact</label>
			<input type="text" class="form-control form-control-sm" name="impact" value="<?php echo isset($impact) ? $impact : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Probability Scale</label>
			<select name="probability" id="probability" class="custom-select custom-select-sm">
				<option value="5" <?php echo isset($probability) && $probability == 5 ? 'selected' : '' ?>>Very Often</option>
				<option value="4" <?php echo isset($probability) && $probability == 4 ? 'selected' : '' ?>>Often</option>
				<option value="3" <?php echo isset($probability) && $probability == 3 ? 'selected' : '' ?>>Occasionally</option>
				<option value="2" <?php echo isset($probability) && $probability == 2 ? 'selected' : '' ?>>Rarely</option>
				<option value="1" <?php echo isset($probability) && $probability == 1 ? 'selected' : '' ?>>Very Rarely</option>
			</select>
		</div>
		<div class="form-group">
			<label for="">Consequence Scale</label>
			<select name="consequence" id="consequence" class="custom-select custom-select-sm">
				<option value="5" <?php echo isset($consequence) && $consequence == 5 ? 'selected' : '' ?>>Very High</option>
				<option value="4" <?php echo isset($consequence) && $consequence == 4 ? 'selected' : '' ?>>High</option>
				<option value="3" <?php echo isset($consequence) && $consequence == 3 ? 'selected' : '' ?>>Medium</option>
				<option value="2" <?php echo isset($consequence) && $consequence == 2 ? 'selected' : '' ?>>Low</option>
				<option value="1" <?php echo isset($consequence) && $consequence == 1 ? 'selected' : '' ?>>Very Low</option>
			</select>
		</div>
		<div class="form-group">
			<select name="status" id="status" class="custom-select custom-select-sm visually-hidden">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Very Low</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Low</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Medium</option>
				<option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>High</option>
				<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Very High</option>
			</select>
		</div>
		<div class="form-group">
			<label for="">Existing Controls</label>
			<input type="text" class="form-control form-control-sm" name="controls" value="<?php echo isset($controls) ? $controls : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Effective/Less Effective</label>
			<select name="effective" id="effective" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($effective) && $effective == 1 ? 'selected' : '' ?>>Effective</option>
				<option value="2" <?php echo isset($effective) && $effective == 2 ? 'selected' : '' ?>>Less Effective</option>
			</select>
		</div>
		<?php if($_SESSION['login_type'] == 2 ): ?>
		<div class="form-group">
			<label for="">Manager Action</label>
			<select name="action" id="action" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($action) && $action == 1 ? 'selected' : '' ?>>Desirable</option>
				<option value="2" <?php echo isset($action) && $action == 2 ? 'selected' : '' ?>>Acceptable</option>
                <option value="3" <?php echo isset($action) && $action == 3 ? 'selected' : '' ?>>Undesirable</option>
                <option value="4" <?php echo isset($action) && $action == 4 ? 'selected' : '' ?>>Unacceptable</option>
                <option value="5" <?php echo isset($action) && $action == 5 ? 'selected' : '' ?>>Catastrophic</option>
			</select>
		</div>
		<?php endif; ?>
		
	</form>
</div>

<script>
	$(document).ready(function(){


	$('.summernote').summernote({
        height: 200,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ]
    })
     })
    
    $('#manage-risk').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_risk',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>
<script>
    function updateRiskStatus() {
        var probabilityValue = parseInt(document.getElementById('probability').value);
        var consequenceValue = parseInt(document.getElementById('consequence').value);

        var riskStatusValue = probabilityValue * consequenceValue;

        var riskStatusDropdown = document.getElementById('status');

        if (riskStatusValue === 1) {
            riskStatusDropdown.value = "1"; // Very Low
        } else if (consequenceValue === 5 && riskStatusValue > 14) {
            riskStatusDropdown.value = "5"; // Very High
        } else if (consequenceValue === 5 && riskStatusValue < 15) {
            riskStatusDropdown.value = "4"; // High
        } else if (consequenceValue === 4) {
            if (riskStatusValue > 15) {
                riskStatusDropdown.value = "5"; // Very High
            } else if (riskStatusValue === 12) {
                riskStatusDropdown.value = "4"; // High
            } else if (riskStatusValue < 12) {
                riskStatusDropdown.value = "3"; // Medium
            }
        } else if (consequenceValue === 3) {
            if (riskStatusValue === 15) {
                riskStatusDropdown.value = "5"; // Very High
            } else if (riskStatusValue < 13 && riskStatusValue > 8) {
                riskStatusDropdown.value = "4"; // High
            } else if (riskStatusValue === 6) {
                riskStatusDropdown.value = "3"; // Medium
            } else if (riskStatusValue === 3) {
                riskStatusDropdown.value = "2"; // Low
            }
        } else if (consequenceValue === 2) {
            if (riskStatusValue === 10) {
                riskStatusDropdown.value = "4"; // High 
            } else if (riskStatusValue < 9 && riskStatusValue > 5) {
                riskStatusDropdown.value = "3"; // Medium
            } else if (riskStatusValue < 5) {
                riskStatusDropdown.value = "2"; // Low
            }
        } else if (consequenceValue === 1) {
            if (riskStatusValue < 6 && riskStatusValue > 3) {
                riskStatusDropdown.value = "3"; // Medium
            } else if (riskStatusValue < 4 && riskStatusValue > 2) {
                riskStatusDropdown.value = "2"; // Low
            }
        }
    }

    document.getElementById('probability').addEventListener('change', updateRiskStatus);
    document.getElementById('consequence').addEventListener('change', updateRiskStatus);

    updateRiskStatus();
</script>