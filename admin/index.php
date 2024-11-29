<?php
session_start();
require '../config/config.php';
// print_r($_SESSION['role']);
if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) ) {
  header('Location: login.php');
  exit();
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
                <h3 class="card-title">Bordered Table</h3>
              </div>
              <!--  -->
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="add.php" type="button" class="btn btn-success">New Blog Post</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                   
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float: right;">
              
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
  
  