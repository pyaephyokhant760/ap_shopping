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
                <h3 class="card-title">Product Table</h3>
              </div>
              <?php
              if(!empty($_GET['pagenu'])) {
                $pagenu = $_GET['pagenu'];
              } else {
                $pagenu = 1;
              }
              $numOfrecs = 5;
              $offset = ($pagenu-1)*$numOfrecs;

              if(empty($_POST['search']) && empty($_COOKIE['search'])) {
                $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
                $stmt->execute();
                $raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
                $total_pagenu = ceil(count($raw_result) / $numOfrecs);
                
                $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
              }else{
                $searchKey = isset($_POST["search"]) ? $_POST["search"] : (isset($_COOKIE["search"]) ? $_COOKIE["search"] : '');
                $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                $stmt->execute();
                $raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
                $total_pagenu = ceil(count($raw_result) / $numOfrecs);
                

                $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
              }
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="product_add.php" type="button" class="btn btn-success">Create New Product</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nmae</th>
                      <th>Description</th>
                      <th>Category</th>
                      <th>In Stock</th>
                      <th>Price</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                   <?php
                   $i=0;
                   if($result) {
                    foreach($result as $data) {?>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM categories WHERE id=".$data['category_id']);
                    $stmt->execute();
                    $catresult = $stmt->fetchAll();
                    
                    ?>
                       <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $data['name']; ?></td>
                      <td><?php echo substr($data['description'],0,30); ?></td>
                      <td><?php echo $catresult[0]['name']; ?></td>
                      <td><?php echo $data['quantity']; ?></td>
                      <td><?php echo $data['price']; ?></td>
                      <td>
                       <div class="btn-group">
                        <div class="container">
                          <a href="product_edit.php?id=<?php echo $data['id']; ?>" type="button" class="btn btn-danger">Edit</a>
                         </div>
                          <div class="container">
                            <a href="product_delete.php?id=<?php echo $data['id']; ?>" type="button" class="btn btn-warning">Delete</a>
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
  
  