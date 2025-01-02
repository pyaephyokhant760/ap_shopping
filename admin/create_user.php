<?php
session_start();
require '../config/config.php';
if($_SESSION['role'] == 1) {
  header('Location: login.php');
} elseif(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
  header('Location: login.php');
}
if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['address']) || empty($_POST['phone']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
    if (empty($_POST['name'])) {
      $nameError = 'Name Could Not Be Null';
    }
    if (empty($_POST['email'])) {
      $emailError = 'Email Could Not Be Null';
    }
    if (empty($_POST['address'])) {
      $addressError = 'address Could Not Be Null';
    }
    if (empty($_POST['phone'])) {
      $phoneError = 'phone Could Not Be Null';
    }
    if (empty($_POST['password'])) {
      $passwordError = 'Password Could Not Be Null';
    }
    if (strlen($_POST['password']) < 4) {
      $passwordError = 'Password Should Be 4 Character at least';
    }
  } else {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if ($_POST['role'] == 'admin') {
      $role = 1;
    } else {
      $role = 0;
    }
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND id!= :id");
    $stmt->execute(array(':email' => $email, ':id' => $id));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);



    if ($user) {
      echo "<script>alert('Already Email')</script>";
    } else {
      $stmt = $conn->prepare('INSERT INTO users(name, email,address,phone,role,password) VALUES (:name, :email, :address, :phone, :role, :password)');
      $result = $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':address' => $address,
        ':phone' => $phone,
        ':password' => $password,
        ':role' => $role
      ]);

      if ($result) {
        echo "<script>alert('Success');window.location.href='user.php';</script>";
      }
    }
  }
}
?>
<?php include('header.php') ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="create_user.php" method="post">
              <div class="form-group">
                <label for="">Name</label>
                <p style="color:red"><?php echo empty($nameError) ? '' : '*' . $nameError ?></p>
                <input type="hidden" name="id">
                <input type="text" name="name" id="" class="form-control">
              </div>
              <div class="form-group">
                <label for="">Email</label>
                <p style="color:red"><?php echo empty($emailError) ? '' : '*' . $emailError ?></p>
                <input type="email" name="email" class="form-control">
              </div>
              <div class="form-group">
                <label for="">Address</label>
                <p style="color:red"><?php echo empty($addressError) ? '' : '*' . $addressError ?></p>
                <input type="text" name="address" id="" class="form-control">
              </div>
              <div class="form-group">
                <label for="">Phone</label>
                <p style="color:red"><?php echo empty($phoneError) ? '' : '*' . $phoneError ?></p>
                <input type="number" name="phone" id="" class="form-control">
              </div>
              <div class="form-group">
                <label for="">Password</label>
                <p style="color:red"><?php echo empty($passwordError) ? '' : '*' . $passwordError ?></p>
                <input type="password" name="password" class="form-control">
              </div>
              <div class="form-check">
                <input type="hidden" name="role" value="user">
                <input class="form-check-input" type="checkbox" value="admin" id="flexCheckIndeterminate" name="role">
                <label class="form-check-label" for="flexCheckIndeterminate">
                  Admin
                </label>
              </div><br>
              <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-danger">
                <a href="user.php" class="btn btn-danger">Back</a>
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