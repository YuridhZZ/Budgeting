<?php
include 'db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <!-- Include DataTables Buttons CSS and JS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

    <!-- Your other styles and scripts go here -->
    <style>
    table.table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }

    table.table-striped tbody tr:nth-child(even) {
        background-color: white;
    }
    </style>
</head>
<body>

<div class="col-md-12">
    <!-- Budget Report Section -->
    <div class="card card-outline card-success">
        <div class="card-header">
            <b>Budgeting Report</b>
            <div class="float-right">
                <!-- Excel and PDF buttons for Budget Report -->
                <button class="btn btn-danger btn-sm" onclick="exportReport('pdf', 'tableBudget')">Export to PDF</button>
                <a href="export_reports_admin.php" class="btn btn-flat btn-sm bg-gradient-success btn-success"><i class="fa fa-file-excel"></i> Export to Excel</a>
            </div>
        </div>
        <div class="table-responsive" id="printable">
            <?php
                // Fetch data from the 'budget' table
                $sql = "SELECT * FROM budget";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table id="tableBudget" class="table m-0 table-bordered table-striped" style="width: 100%;">
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
                } else {
                    echo 'No data available.';
                }

                // Fetch user information based on session ID
                $userId = $_SESSION['login_id'];
                $userQuery = "SELECT firstname, lastname FROM users WHERE id = '$userId'";
                $userResult = $conn->query($userQuery);

                if ($userResult->num_rows > 0) {
                    $userRow = $userResult->fetch_assoc();
                    $userName = $userRow['firstname'] . ' ' . $userRow['lastname'];
                }

                // Close the database connection
            ?>

            </div>
    </div>
</div>

<div class="col-md-12">
    <!-- Risk Report Section -->
    <div class="card card-outline card-success">
        <div class="card-header">
            <b>Risk Report</b>
            <div class="float-right">
                <!-- Excel and PDF buttons for Risk Report -->
                <button class="btn btn-success btn-sm ml-2" onclick="exportReport('excel', 'tableRisk')">Export to Excel</button>
                <button class="btn btn-danger btn-sm" onclick="exportReport('pdf', 'tableRisk')">Export to PDF</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="printable2">
            <?php
                // Fetch data from the 'risk_list' table
                $sql = "SELECT risk, owner, cause, impact, controls, effective, status, action FROM risk_list";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table id="tableRisk" class="table m-0 table-bordered table-striped" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Risk Description</th>
                                    <th>Risk Owner</th>
                                    <th>Risk Cause</th>
                                    <th>Risk Impact</th>
                                    <th>Existing Controls</th>
                                    <th>Effective/Less Effective</th>
                                    <th>Risk Status</th>
                                    <th>Manager Action</th>
                                </tr>
                            </thead>
                            <tbody>';

                    $i = 1; // Initialize a counter variable

                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td class="text-center">' . $i++ . '</td>
                                <td class=""><b>' . ucwords($row['risk']) . '</b></td>
                                <td class=""><b>' . ucwords($row['owner']) . '</b></td>
                                <td class=""><b>' . ucwords($row['cause']) . '</b></td>
                                <td class=""><b>' . ucwords($row['impact']) . '</b></td>
                                <td class=""><b>' . ucwords($row['controls']) . '</b></td>
                                <td>';

                        // Display Effective/Less Effective badge
                        if ($row['effective'] == 1) {
                            echo "<span class='badge badge-light'>Effective</span>";
                        } elseif ($row['effective'] == 2) {
                            echo "<span class='badge badge-dark'>Less Effective</span>";
                        }

                        echo '</td>
                                <td>';

                        // Display Risk Status badge
                        if ($row['status'] == 1) {
                            echo "<span class='badge badge-pill badge-primary'>Very Low</span>";
                        } elseif ($row['status'] == 2) {
                            echo "<span class='badge badge-pill badge-success'>Low</span>";
                        } elseif ($row['status'] == 3) {
                            echo "<span class='badge badge-pill badge-secondary'>Medium</span>";
                        } elseif ($row['status'] == 4) {
                            echo "<span class='badge badge-pill badge-warning'>High</span>";
                        } elseif ($row['status'] == 5) {
                            echo "<span class='badge badge-pill badge-danger'>Very High</span>";
                        }

                        echo '</td>
                                <td>';

                        // Display Manager Action badge
                        if ($row['action'] == 0) {
                            echo "<span class='badge badge-pill badge-light'>Pending</span>";
                        } elseif ($row['action'] == 1) {
                            echo "<span class='badge badge-pill badge-primary'>Desirable</span>";
                        } elseif ($row['action'] == 2) {
                            echo "<span class='badge badge-pill badge-success'>Acceptable</span>";
                        } elseif ($row['action'] == 3) {
                            echo "<span class='badge badge-pill badge-secondary'>Undesirable</span>";
                        } elseif ($row['action'] == 4) {
                            echo "<span class='badge badge-pill badge-warning'>Unacceptable</span>";
                        } elseif ($row['action'] == 5) {
                            echo "<span class='badge badge-pill badge-danger'>Catastrophic</span>";
                        }

                        echo '</td>
                            </tr>';
                    }

                    echo '</tbody></table>';
                } else {
                    echo 'No data available.';
                }

                // Close the database connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

<script>
    function exportReport(format, tableId) {
        // Initialize DataTable for the specified table
        var table = $('#' + tableId).DataTable({
            dom: 'Bfrtip',
            buttons: [   
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'btn btn-success',
                    init: function (api, node, config) {
                        $(node).hide();
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export to PDF',
                    className: 'btn btn-danger',
                    init: function (api, node, config) {
                        $(node).hide();
                    }
                }
            ]
        });
        if (format === 'excel') {
            table.button('.buttons-excel').trigger();
        } else if (format === 'pdf') {
            table.button('.buttons-pdf').trigger();
        }
    }
</script>

</body>
</html>
