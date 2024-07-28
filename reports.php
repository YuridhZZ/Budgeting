<?php include 'db_connect.php'; ?>

<style>
    table.table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }

    table.table-striped tbody tr:nth-child(even) {
        background-color: white;
    }
    </style>


<div class="col-md-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <b>Budgeting Report</b>
            <div class="card-tools">
                <button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print"><i class="fa fa-print"></i> Print</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="printable">
                <?php
                $where = "";
                $orgUnitName = "";

                if ($_SESSION['login_type'] == 2) {
                    $managerId = $_SESSION['login_id'];
                    $where = " WHERE p.manager_id = '$managerId'";

                    // Fetch organizational unit name based on manager's ID
                    $orgUnitQuery = "SELECT * FROM project_list WHERE manager_id = '$managerId' LIMIT 1";
                    $orgUnitResult = $conn->query($orgUnitQuery);

                    if ($orgUnitResult->num_rows > 0) {
                        $orgUnitRow = $orgUnitResult->fetch_assoc();
                        $orgName = $orgUnitRow['name'];
                        $orgUnitName = $orgUnitRow['unit'];
                    }
                }
                
                // Fetch data from the 'budget' table
                $sql = "SELECT b.* FROM budget b INNER JOIN risk_mitigation r ON b.mitigation_id = r.id INNER JOIN project_list p ON r.project_id = p.id $where";

                $result = $conn->query($sql);


                if ($result->num_rows > 0) {

                    
                    
                    echo '<table class="table m-0 table-bordered table-striped" style="width: 100%;">
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
                    echo '<div style="margin-right: 10px; display: flex; justify-content: flex-end">Total Budget: '. "Rp " . number_format($totalCostSum, 2) . '</div>';
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
</div>

<div class="col-md-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <b>Risk Report</b>
            <div class="card-tools">
                <button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print2"><i class="fa fa-print"></i> Print</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="printable2">
                <?php
                // Fetch data from the 'risk_list' table
                if ($_SESSION['login_type'] == 1) {
                    // For admin, show all data
                    $sql = "SELECT * FROM risk_list";
                } elseif ($_SESSION['login_type'] == 2) {
                    // For manager, show data based on manager_id
                    $sql = "SELECT r.*, p.manager_id FROM risk_list r
                            INNER JOIN project_list p ON r.project_id = p.id
                            WHERE p.manager_id = '{$_SESSION['login_id']}'";
                }
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {

                    
                    echo '<table class="table m-0 table-bordered table-striped" style="width: 100%;">
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

<script>
    $('#print').click(function () {
        start_load()
        var _h = $('head').clone()
        var _p = $('#printable').clone()
        var _d = "<h1 class='text-center'><b>Budgeting Documents for 2024</b></h1>"
        
        var _c = "<p><b>Allocation: <?php echo "Rp " . number_format($totalCostSum, 2)  ?></b></p>"
        var _u = "<p><small>Print By: <?php echo $userName; ?></small></p>" // Include user's name
        var _a = "<p>Organizational Unit: <?php echo $orgName; ?></p>"
        var _b = "<p>Unit: <?php echo $orgUnitName; ?></p>"

        _p.prepend(_u)
        _p.prepend(_c)
        _p.prepend(_b)
        _p.prepend(_a)
        
        _p.prepend(_d)
        
        _p.prepend(_h)
        var nw = window.open("", "", "width=900,height=600")
        nw.document.write(_p.html())
        nw.document.close()
        nw.print()
        setTimeout(function () {
            nw.close()
            end_load()
        }, 750)
    })
</script>
<script>
    $('#print2').click(function () {
        start_load()
        var _h = $('head').clone()
        var _p = $('#printable2').clone()
        var _d = "<h1 class='text-center'><b>Risk Report</b></h1>"
        var _u = "<p><small>Print by: <?php echo $userName; ?></small></p>" // Include user's name
        var _a = "<p>Organizational Unit: <?php echo $orgName; ?></p>"
        var _b = "<p>Unit: <?php echo $orgUnitName; ?></p>"

        _p.prepend(_u)
        _p.prepend(_b)
        _p.prepend(_a)
        _p.prepend(_d)
        
        _p.prepend(_h)
        var nw = window.open("", "", "width=900,height=600")
        nw.document.write(_p.html())
        nw.document.close()
        nw.print()
        setTimeout(function () {
            nw.close()
            end_load()
        }, 750)
    })
</script>
