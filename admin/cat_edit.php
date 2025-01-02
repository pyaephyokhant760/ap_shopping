<?php
session_start();
require '../config/config.php';
require '../config/common.php';
if($_SESSION['role'] == 1) {
  header('Location: login.php');
} elseif(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
  header('Location: login.php');
}

if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['description'])) {
    if (empty($_POST['name'])) {
      $nameError = 'Name Could Not Be Null';
    }
    if (empty($_POST['description'])) {
      $descriptionError = 'Description Could Not Be Null';
    }
  } else {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE categories SET name='$name',description='$description' WHERE id='$id'");
    $result = $stmt->execute();
    if ($result) {
      echo "<script>alert('Category Update Success');window.location.href='category.php';</script>";
    }
  }
}

$stmt = $conn->prepare("SELECT * FROM categories WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_DEFAULT);

?>
<?php include('header.php') ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="" method="post">
              <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
              <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
              <div class="form-group">
                <label for="title">Nmae</label>
                <p style="color:red"><?php echo empty($nameError) ? '' : '*' . $nameError ?></p>
                <input type="text" name="name" id="nmae" class="form-control" value="<?php echo $result[0]['name']; ?>">
              </div>

              <div class="form-group">
                <label for="">Description</label>
                <p style="color:red"><?php echo empty($descriptionError) ? '' : '*' . $descriptionError ?></p>
                <textarea name="description" id="" class="form-control"><?php echo $result[0]['description']; ?></textarea>
              </div>
              <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-danger">
                <a href="category.php" class="btn btn-danger">Back</a>
              </div>
            </form>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<?php include('footer.html') ?>