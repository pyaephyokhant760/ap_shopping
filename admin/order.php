<?php
session_start();
require '../config/config.php';
if($_SESSION['role'] == 1) {
  header('Location: login.php');
} elseif(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
  header('Location: login.php');
}
if (isset($_POST["search"])) {
  setcookie("search", $_POST["search"], time() + (86400 * 30), "/");
} else {
  if (empty($_GET["pagenu"])) {
      unset($_COOKIE["search"]);
      setcookie("search", "", time() - 3600, "/");
  }
}



?>
<?php include ('header.php') ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Table</h3>
              </div>
              <?php
              if(!empty($_GET['pagenu'])) {
                $pagenu = $_GET['pagenu'];
              } else {
                $pagenu = 1;
              }
              $numOfrecs = 5;
              $offset = ($pagenu-1)*$numOfrecs;

              
                

                $stmt = $conn->prepare("SELECT * FROM sale_orders  ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                   <?php
                   $i=0;
                   if($result) {
                    foreach($result as $data) {?>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM users WHERE id=".$data['customer_id']);
                    $stmt->execute();
                    $catresult = $stmt->fetchAll();
                    ?>
                       <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $catresult[0]['name']; ?></td>
                      <td><?php echo $data['total_price']; ?></td>
                      <td><?php echo date('Y-m-d',strtotime($data['order_date']))?></td>
                      <td>
                       <div class="btn-group">
                        <div class="container">
                          <a href="order_detail.php?id=<?php echo $data['id']; ?>" type="button" class="btn btn-default">View</a>
                         </div>
                       </div>
                      </td>
                    </tr>
                    <?php
                    $i++;
                    }
                   }
                   
                   ?>
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float: right;">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pagenu=1">Frist</a></li>
                  <li class="page-item <?php if($pagenu <= 1) { echo 'disabled'; }?>"><a class="page-link" href="<?php if($pagenu <= 1) {echo "";} else { echo '?pagenu='.($pagenu-1);}?>">Previous</a></li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pagenu; ?></a></li>
                  <li class="page-item <?php if($pagenu >= $total_pagenu) { echo 'disabled'; }?>"><a class="page-link" href="<?php if($pagenu >= $total_pagenu) {echo "";} else { echo '?pagenu='.($pagenu+1);}?>">Next</a></li>
                  <li class="page-item"><a class="page-link" href="?pagenu=<?php echo $total_pagenu?>">Last</a></li>
                </ul>
              </nav>
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <?php include ('footer.html') ?>
  
  