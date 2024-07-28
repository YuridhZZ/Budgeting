<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM risk_mitigation where id = ".$_GET['id'])->fetch_array();
    foreach($qry as $k => $v){
        $$k = $v;
    }
}
if(isset($id)){
    $qry = $conn->query("SELECT * FROM budget where mitigation_id = ".$_GET['id'])->fetch_array();
    foreach($qry as $k => $v){
        $$k = $v;
    }
}
?>

<div class="container-fluid">
    <form action="" id="manage-mitigation" method="post">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">

        <div class="form-group">
            <label for="" class="control-label">Risk Description</label>
            <select class="form-control form-control-sm select2" name="risk_id" id="riskSelect">
                <option></option>
                <?php 
                $risks = $conn->query("SELECT * FROM risk_list where project_id = {$_GET['pid']} order by risk asc ");
                while($row= $risks->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" data-status="<?php echo $row['status']; ?>" <?php echo isset($risk_id) && $risk_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['risk']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="">Risk Mitigation</label>
            <input type="text" name="subject" class="form-control form-control-sm"  required value="<?php echo isset($subject) ? $subject : '' ?>">
        </div>
        
		
        <?php if(!isset($_GET['mid'])): ?>    
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <table class="table table-condensed table-striped" id="invoiceItem">
                    <tr>
                        <th width="2%">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="checkAll" name="checkAll">
                                <label class="custom-control-label" for="checkAll"></label>
                            </div>
                        </th>
                        <th width="15%">Code</th>
                        <th width="30%">Detail</th>
                        <th width="5%">Quantity</th>
                        <th width="10%">Unit</th>
                        <th width="15%">Price</th>
                        <th width="15%">Total</th>
                    </tr>
                    
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="itemRow custom-control-input" id="itemRow_1">
                                <label class="custom-control-label" for="itemRow_1"></label>
                            </div>
                        </td>
                        <td><input type="text" name="code[]" id="Code_1" class="form-control" autocomplete="off" value="<?php echo isset($code) ? $code : '' ?>"></td>
                        <td><input type="text" name="detail[]" id="Detail_1" class="form-control" autocomplete="off" value="<?php echo isset($detail) ? $detail : '' ?>"></td>
                        <td><input type="number" name="quantity[]" id="Quantity_1" class="form-control quantity" autocomplete="off" value="<?php echo isset($quantity) ? $quantity : '' ?>"></td>
                        <td><input type="text" name="units[]" id="Units_1" class="form-control" autocomplete="off" value="<?php echo isset($units) ? $units : '' ?>"></td>
                        <td><input type="number" name="price[]" id="Price_1" class="form-control price" autocomplete="off" value="<?php echo isset($price) ? $price : '' ?>"></td>
                        <td><input type="number" name="total_cost[]" id="Total_1" class="form-control total" autocomplete="off" value="<?php echo isset($total_cost) ? $total_cost : '' ?>"></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-danger delete" id="removeRows" type="button">- Delete</button>
                <button class="btn btn-success" id="addRows" type="button">+ Add More</button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="form-group mt-3 mb-3">
                    <label>Budget: &nbsp;</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text currency">Rp</span>
                        </div>
                        <input value="<?php echo isset($subTotal) ? $subTotal : '' ?>" type="number" class="form-control" name="subTotal" id="subTotal" placeholder="Subtotal">
                    </div>
                    <div id="warning" style="color: red;"></div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <input type="hidden" name="mitigation_id" value="<?php echo isset($_GET['mid']) ? $_GET['mid'] : '' ?>">
        <?php endif; ?>
    </form>

</div>

<script>
    $(document).ready(function(){
    $(document).on('click', '#checkAll', function() {          	
        $(".itemRow").prop("checked", this.checked);
    });	
    $(document).on('click', '.itemRow', function() {  	
        if ($('.itemRow:checked').length == $('.itemRow').length) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }
    });  
    var count = $(".itemRow").length;
    $(document).on('click', '#addRows', function() { 
        count++;
        var htmlRows = '';
        htmlRows += '<tr>';
        htmlRows += '<td><div class="custom-control custom-checkbox"> <input type="checkbox" class="custom-control-input itemRow" id="itemRow_'+count+'"> <label class="custom-control-label" for="itemRow_'+count+'"></label> </div></td>';          
        htmlRows += '<td><input type="text" name="code[]" id="Code_'+count+'" class="form-control" autocomplete="off"></td>';          
        htmlRows += '<td><input type="text" name="detail[]" id="Detail_'+count+'" class="form-control" autocomplete="off"></td>';	
        htmlRows += '<td><input type="number" name="quantity[]" id="Quantity_'+count+'" class="form-control quantity" autocomplete="off"></td>';
        htmlRows += '<td><input type="text" name="units[]" id="Units_'+count+'" class="form-control" autocomplete="off"></td>';   		
        htmlRows += '<td><input type="number" name="price[]" id="Price_'+count+'" class="form-control price" autocomplete="off"></td>';		 
        htmlRows += '<td><input type="number" name="total_cost[]" id="Total_'+count+'" class="form-control total" autocomplete="off"></td>';          
        htmlRows += '</tr>';
        $('#invoiceItem').append(htmlRows);
    }); 
			 
    $(document).on('click', '#removeRows', function(){
        $(".itemRow:checked").each(function() {
            $(this).closest('tr').remove();
        });
        $('#checkAll').prop('checked', false);
        calculateTotal();
    });		
    $(document).on('blur', "[id^=Quantity_]", function(){
        calculateTotal();
    });	
    $(document).on('blur', "[id^=Price_]", function(){
        calculateTotal();
    });	
    $(document).on('click', '.deleteInvoice', function(){
        var id = $(this).attr("id");
        if(confirm("Are you sure you want to remove this?")){
            $.ajax({
                url:"action.php",
                method:"POST",
                dataType: "json",
                data:{id:id, action:'delete_invoice'},				
                success:function(response) {
                    if(response.status == 1) {
                        $('#'+id).closest("tr").remove();
                    }
                }
            });
        } else {
            return false;
        }
    });
});	
function calculateTotal() {
    var totalAmount = 0; 
    $("[id^='Price_']").each(function() {
        var id = $(this).attr('id');
        id = id.replace("Price_", '');
        var price = $('#Price_' + id).val();
        var quantity  = $('#Quantity_' + id).val();
        if (!quantity) {
            quantity = 1;
        }
        var total = price * quantity;
        $('#Total_' + id).val(parseFloat(total));
        totalAmount += total;			
    });

    $('#subTotal').val(parseFloat(totalAmount));

    // Budget limit check
    var maxBudget = 0;

    // Get the selected risk status from the dropdown
    var riskStatus = $("#riskSelect option:selected").data("status");

    // Set the maximum budget based on risk status
    switch (riskStatus) {
        case 1:
            maxBudget = 50000000;
            break;
        case 2:
            maxBudget = 200000000;
            break;
        case 3:
            maxBudget = 1000000000;
            break;
        case 4:
            maxBudget = 50000000000;
            break;
        case 5:
            maxBudget = 100000000000;
            break;
        default:
            maxBudget = 0;
            break;
    }

    // Display warning if the total exceeds the maximum budget
    var inputVal = parseFloat($('#subTotal').val());

    if (inputVal > maxBudget) {
        $('#warning').text('Warning: Maximum budget is Rp ' + maxBudget);
    } else {
        $('#warning').text('');
    }
}

$('#subTotal').on('blur', function() {
calculateTotal();
});

// Update the warning message when the risk selection changes
$('#riskSelect').on('change', function() {
calculateTotal();
});
</script>

<script>
$(document).ready(function() {

    $("#removeRows").click(function() {
        $(".itemRow:checked").each(function() {
            $(this).closest("tr").remove();
        });
        $('#checkAll').prop('checked', false);
        calculateTotal();
    });

    // Submit the Risk Description form via AJAX
    $("#manage-mitigation").on("submit", function(event) {
        event.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "ajax.php?action=save_mitigation", 
            method: "POST",
            data: formData,
            success:function(resp){
                if(resp == 1){
                    alert_toast('Data successfully saved',"success");
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            },
            error: function() {
                // Handle errors here
                alert("An error occurred while saving the Risk Description.");
            }
        });
    });
});
</script>