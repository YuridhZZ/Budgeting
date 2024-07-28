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
			<input type="text" class="form-control form-control-sm" name="risk" value="<?php echo isset($risk) ? $risk : '' ?>" required disabled>
		</div>
		<div class="form-group">
			<label for="">Risk Owner</label>
			<input type="text" class="form-control form-control-sm" name="owner" value="<?php echo isset($owner) ? $owner : '' ?>" required disabled>
		</div>
		<div class="form-group">
			<label for="">Risk Cause</label>
			<input type="text" class="form-control form-control-sm" name="cause" value="<?php echo isset($cause) ? $cause: '' ?>" required disabled>
		</div>
		<div class="form-group">
			<label for="">Risk Impact</label>
			<input type="text" class="form-control form-control-sm" name="impact" value="<?php echo isset($impact) ? $impact : '' ?>" required disabled>
		</div>
		<div class="form-group">
			<label for="">Risk Status</label>
			<select name="status" id="status" class="custom-select custom-select-sm" required disabled>
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Very Low</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Low</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Medium</option>
				<option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>High</option>
				<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Very High</option>
			</select>
		</div>
		<div class="form-group">
			<label for="">Existing Controls</label>
			<input type="text" class="form-control form-control-sm" name="controls" value="<?php echo isset($controls) ? $controls : '' ?>" required disabled>
		</div>
		<div class="form-group">
			<label for="">Effective/Less Effective</label>
			<select name="effective" id="effective" class="custom-select custom-select-sm" required disabled>
				<option value="1" <?php echo isset($effective) && $effective == 1 ? 'selected' : '' ?>>Effective</option>
				<option value="2" <?php echo isset($effective) && $effective == 2 ? 'selected' : '' ?>>Less Effective</option>
			</select>
		</div>
		<hr>
		<hr>
		<div class="form-group">
            <label for="manager-action-description">Manager Action Descriptions</label>
            <p>Desirable = The budget issued is preferred, and does not require a monitoring process<br>
            Acceptable = The budget issued is accepted, and a monitoring process is required.<br>
            Undesirable = The budget spent is undesirable, but action is required<br>
            Unacceptable = The budget issued is not accepted, but urgent action is required<br>
            Catastrophic = Budget that can disrupt and damage the business and must be stopped immediately.</p>
        </div>
		<hr>
		<hr>
		<div class="form-group">
			<label for="">Probability Scale</label>
			<select name="probability_m" id="probability_m" class="custom-select custom-select-sm">
				<option value="5" <?php echo isset($probability_m) && $probability_m == 5 ? 'selected' : '' ?>>Very Often</option>
				<option value="4" <?php echo isset($probability_m) && $probability_m == 4 ? 'selected' : '' ?>>Often</option>
				<option value="3" <?php echo isset($probability_m) && $probability_m == 3 ? 'selected' : '' ?>>Occasionally</option>
				<option value="2" <?php echo isset($probability_m) && $probability_m == 2 ? 'selected' : '' ?>>Rarely</option>
				<option value="1" <?php echo isset($probability_m) && $probability_m == 1 ? 'selected' : '' ?>>Very Rarely</option>
			</select>
		</div>
		<div class="form-group">
			<label for="">Consequence Scale</label>
			<select name="consequence_m" id="consequence_m" class="custom-select custom-select-sm">
				<option value="5" <?php echo isset($consequence_m) && $consequence_m == 5 ? 'selected' : '' ?>>Very High</option>
				<option value="4" <?php echo isset($consequence_m) && $consequence_m == 4 ? 'selected' : '' ?>>High</option>
				<option value="3" <?php echo isset($consequence_m) && $consequence_m == 3 ? 'selected' : '' ?>>Medium</option>
				<option value="2" <?php echo isset($consequence_m) && $consequence_m == 2 ? 'selected' : '' ?>>Low</option>
				<option value="1" <?php echo isset($consequence_m) && $consequence_m == 1 ? 'selected' : '' ?>>Very Low</option>
			</select>
		</div>
		<div class="form-group">
				<select name="action" id="action" class="custom-select custom-select-sm visually-hidden">
				<option value="1" <?php echo isset($action) && $action == 1 ? 'selected' : '' ?>>Desirable</option>
				<option value="2" <?php echo isset($action) && $action == 2 ? 'selected' : '' ?>>Acceptable</option>
                <option value="3" <?php echo isset($action) && $action == 3 ? 'selected' : '' ?>>Undesirable</option>
                <option value="4" <?php echo isset($action) && $action == 4 ? 'selected' : '' ?>>Unacceptable</option>
                <option value="5" <?php echo isset($action) && $action == 5 ? 'selected' : '' ?>>Catastrophic</option>
			</select>
		</div>
        <div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="" class="control-label">Notes</label>
					<textarea name="notes" id="notes" cols="30" rows="10" class="summernote form-control">
						<?php echo isset($notes) ? $notes : '' ?>
					</textarea>
				</div>
			</div>
		</div>
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
    // Function to calculate and update the risk action
    function updateRiskAction() {
        var probabilityValue = parseInt(document.getElementById('probability_m').value);
        var consequenceValue = parseInt(document.getElementById('consequence_m').value);

        // Perform the multiplication to get the new risk action value
        var actionValue = probabilityValue * consequenceValue;

        // Set the risk action dropdown based on the calculated value
        var actionDropdown = document.getElementById('action');
        if (actionValue === 1) {
            actionDropdown.value = "1"; // Very Low
        } else if (consequenceValue === 5 && actionValue > 14) {
            actionDropdown.value = "5"; // Very High
        } else if (consequenceValue === 5 && actionValue <= 14) {
            actionDropdown.value = "4"; // High
        } else if (consequenceValue === 4) {
            if (actionValue > 15) {
                actionDropdown.value = "5"; // Very High
            } else if (actionValue === 12) {
                actionDropdown.value = "4"; // High
            } else if (actionValue <= 12) {
                actionDropdown.value = "3"; // Medium
            }
        } else if (consequenceValue === 3) {
            if (actionValue === 15) {
                actionDropdown.value = "5"; // Very High
            } else if (actionValue <= 13 && actionValue > 8) {
                actionDropdown.value = "4"; // High
            } else if (actionValue === 6) {
                actionDropdown.value = "3"; // Medium
            } else if (actionValue === 3) {
                actionDropdown.value = "2"; // Low
            }
        } else if (consequenceValue === 2) {
            if (actionValue === 10) {
                actionDropdown.value = "4"; // High
            } else if (actionValue <= 9 && actionValue > 5) {
                actionDropdown.value = "3"; // Medium
            } else if (actionValue <= 5) {
                actionDropdown.value = "2"; // Low
            }
        } else if (consequenceValue === 1) {
            if (actionValue <= 6 && actionValue > 3) {
                actionDropdown.value = "3"; // Medium
            } else if (actionValue <= 4 && actionValue > 2) {
                actionDropdown.value = "2"; // Low
            }
        } else {
            // If none of the conditions are met, set a default value or handle it as needed
            actionDropdown.value = "1"; // Default to Very Low
        }
    }

    // Add event listeners to trigger the updateRiskAction function when either probability or consequence changes
    document.getElementById('probability_m').addEventListener('change', updateRiskAction);
    document.getElementById('consequence_m').addEventListener('change', updateRiskAction);

    // Initial calculation when the page loads
    updateRiskAction();
</script>
