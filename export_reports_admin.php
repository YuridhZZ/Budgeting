<?php include 'db_connect.php';

header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=Reports Admin Budget Business Plan.xls");
?>

<style>
    table.table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }

    table.table-striped tbody tr:nth-child(even) {
        background-color: white;
    }
</style>

<p allign="center" style="font-weight:bold;font-size:16pt"> BUDGETING REPORT FOR 2024</p>

<div class="col-md-12 mx-auto text-center" allign="center">
    <div class="card card-outline card-success">
        <div class="card-body p-0">
            <div class="table-responsive" id="printable">
                <?php
                // Fetch data from the 'budget' table
                $sql = "SELECT * FROM budget";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table id="tableBudget" allign="center" border="1" class="table m-0 table-bordered table-striped" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Details/Program/Output/Component/Sub-Component/Activity</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>';
                    
                    $totalCostSum = 0; // Initialize total cost sum variable

                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . $row['code'] . '</td>
                                <td>' . $row['detail'] . '</td>
                                <td>' . $row['quantity'] . ' ' . $row['units'] . '</td>
                                <td>' . $row['price'] . '</td>
                                <td>' . $row['total_cost'] . '</td>
                            </tr>';

                        // Add the total_cost to the sum
                        $totalCostSum += $row['total_cost'];
                    }

                    echo '</tbody></table>';

                    // Display the total cost sum

                    echo '<div style="display: flex; justify-content: flex-end">Total Budget: '. "Rp " . number_format($totalCostSum, 2) . '</div>';
                } else {
                    echo 'No data available.';
                }

                // Close the database connection
                ?>
            </div>
        </div>
    </div>
</div>