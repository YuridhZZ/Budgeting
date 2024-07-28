<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
 <div class="col-12">
          <div class="card">
            <div class="card-body">
              Welcome <?php echo $_SESSION['login_name'] ?>!
            </div>
          </div>
  </div>
  <hr>
  <?php 

    $where = "";
    if($_SESSION['login_type'] == 2){
      $where = " where manager_id = '{$_SESSION['login_id']}' ";
    }elseif($_SESSION['login_type'] == 3){
      $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
    }
     $where2 = "";
    if($_SESSION['login_type'] == 2){
      $where2 = " where p.manager_id = '{$_SESSION['login_id']}' ";
    }elseif($_SESSION['login_type'] == 3){
      $where2 = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
    }
    ?>
        
      <div class="row">
        <div class="col-md-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <b>Dashboard</b>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0 table-hover">
                <colgroup>
                  <col width="5%">
                  <col width="20%">
                  <col width="15%">
                  <col width="15%">
                  <col width="15%">
                  <col width="15%">
                  <col width="15%">
                </colgroup>
                <thead>
                  <th>#</th>
                  <th>Project</th>
                  <th>Project Status</th>
                  <th>Risk</th>
                  <th>Completed Risk</th>
                  <th>Project Progress</th>
                  <th></th>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
                $where = "";
                if($_SESSION['login_type'] == 2){
                  $where = " where manager_id = '{$_SESSION['login_id']}' ";
                }elseif($_SESSION['login_type'] == 3){
                  $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
                }
                $qry = $conn->query("SELECT * FROM project_list $where order by name asc");
                while($row= $qry->fetch_assoc()):
                  $prog= 0;
                $tprog = $conn->query("SELECT * FROM risk_list where project_id = {$row['id']}")->num_rows;
                $cprog = $conn->query("SELECT * FROM risk_list WHERE project_id = {$row['id']} AND action NOT IN (0, 5)")->num_rows;
                $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
                $prod = $conn->query("SELECT * FROM risk_mitigation where project_id = {$row['id']}")->num_rows;
                if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                if($prod  > 0  || $cprog > 0)
                  $row['status'] = 2;
                else
                  $row['status'] = 1;
                elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                $row['status'] = 4;
                endif;
                  ?>
                  <tr>
                      <td>
                         <?php echo $i++ ?>
                      </td>
                      <td>
                          <a>
                              <?php echo ucwords($row['name']) ?>
                          </a>
                          <br>
                          <small>
                              Due: <?php echo date("Y-m-d",strtotime($row['end_date'])) ?>
                          </small>
                      </td>
                      <td class="project-state">
                          <?php
                            if($stat[$row['status']] =='Pending'){
                              echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Started'){
                              echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='On-Progress'){
                              echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='On-Hold'){
                              echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Over Due'){
                              echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Done'){
                              echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
                            }
                          ?>
                      </td>
                      <td class="text-center">
                      	<?php echo number_format($tprog) ?>
                      </td>
                      <td class="text-center">
                      	<?php echo number_format($cprog) ?>
                      </td>
                      <td class="project_progress">
                          <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                              </div>
                          </div>
                          <small>
                              <?php echo $prog ?>% Complete
                          </small>
                      </td>
                      <td>
                        <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>">
                              <i class="fas fa-folder">
                              </i>
                              View
                        </a>
                      </td>
                  </tr>
                <?php endwhile; ?>
                </tbody>  
              </table>
            </div>
          </div>
        </div>
        </div>

        <div class="col-12">
            <div class="small-box bg-light shadow-sm border text-center">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT SUM(subTotal) AS totalSubTotal FROM risk_mitigation r WHERE risk_id IN (SELECT id FROM risk_list WHERE action NOT IN (0, 5) AND project_id = r.project_id) ");
                    $row = $result->fetch_assoc();
                    $totalSubTotal = $row['totalSubTotal'];
                    $formattedTotalSubTotal = number_format($totalSubTotal, 2); // Format as currency with two decimal places
                    ?>
                    <h3><?php echo "Rp " . $formattedTotalSubTotal; ?></h3>
                    <p>Total Budget</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-md-12">
          <div class="row d-flex justify-content-center">
          <div class="col-2">
            <div class="small-box bg-red shadow-sm border">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS total_high_status FROM risk_list t INNER JOIN project_list p ON p.id = t.project_id $where2 AND t.status = 5");
                    $row = $result->fetch_assoc();
                    $totalHighStatus = $row['total_high_status'];
                    ?>
                    <h3><?php echo $totalHighStatus; ?></h3>
                    <p>Very High Status</p>
                </div>
                <div class="icon">
                    <i class=" fas fa-exclamation-triangle"></i>
                    
                </div>
            </div>
          </div>

          <div class="col-2">
            <div class="small-box bg-orange shadow-sm border">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS total_high_status FROM risk_list t INNER JOIN project_list p ON p.id = t.project_id $where2 AND t.status = 4");
                    $row = $result->fetch_assoc();
                    $totalHighStatus = $row['total_high_status'];
                    ?>
                    <h3><?php echo $totalHighStatus; ?></h3>
                    <p>High Status</p>
                </div>
                <div class="icon">
                <i class="fas fa-sort-amount-up"></i>
                </div>
            </div>
          </div>

          <div class="col-2">
            <div class="small-box bg-yellow shadow-sm border">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS total_high_status FROM risk_list t INNER JOIN project_list p ON p.id = t.project_id $where2 AND t.status = 3");
                    $row = $result->fetch_assoc();
                    $totalHighStatus = $row['total_high_status'];
                    ?>
                    <h3><?php echo $totalHighStatus; ?></h3>
                    <p>Medium Status</p>
                </div>
                <div class="icon">
                    <i class=" far fa-minus-square"></i>
                </div>
            </div>
          </div>

          <div class="col-2">
            <div class="small-box bg-green shadow-sm border">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS total_high_status FROM risk_list t INNER JOIN project_list p ON p.id = t.project_id $where2 AND t.status = 2");
                    $row = $result->fetch_assoc();
                    $totalHighStatus = $row['total_high_status'];
                    ?>
                    <h3><?php echo $totalHighStatus; ?></h3>
                    <p>Low Status</p>
                </div>
                <div class="icon">
                    <i class=" fas fa-sort-amount-down"></i>
                </div>
            </div>
          </div>

          <div class="col-2">
            <div class="small-box bg-teal shadow-sm border">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS total_high_status FROM risk_list t INNER JOIN project_list p ON p.id = t.project_id $where2 AND t.status = 1");
                    $row = $result->fetch_assoc();
                    $totalHighStatus = $row['total_high_status'];
                    ?>
                    <h3><?php echo $totalHighStatus; ?></h3>
                    <p>Very Low Status</p>
                </div>
                <div class="icon">
                <i class="fas fa-angle-double-down"></i>
                </div>
            </div>
          </div>
            
          </div>
        </div>

        <div class="col-md-12">
          <div class="row d-flex justify-content-center">
            <div class="col-4">
              <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                  <h3><?php echo $conn->query("SELECT * FROM project_list $where")->num_rows; ?></h3>
                    <p>Total Projects</p>
                </div>
              <div class="icon">
                <i class="fa fa-layer-group"></i>
            </div>
            </div>
          </div>
          <div class="col-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM risk_list t inner join project_list p on p.id = t.project_id $where2")->num_rows; ?></h3>
                <p>Total Risks</p>
              </div>
              <div class="icon">
                <i class="fa fa-tasks"></i>
              </div>
            </div>
          </div>
        </div>

      </div>

</div>

      

    </div>
  </div>
 