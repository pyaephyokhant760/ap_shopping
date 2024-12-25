<?php
session_start();
require '../config/config.php';
require '../config/common.php';
if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
  header('Location: /admin/login.php');
  exit();
}

if ($_POST) {
  
  if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) || empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])) {
    if (empty($_POST['name'])) {
      $nameError = 'Name Could Not Be Null';
    }
    if (empty($_POST['description'])) {
      $descriptionError = 'Description Could Not Be Null';
    }
    if (empty($_POST['quantity'])) {
      $qtyError = 'Quanlity Could Not Be Null';
    } elseif (empty($_POST['quantity']) && is_numeric($_POST['quantity']) != 1) {
      $qtyError = 'Quanlity Could Not Be Null';
    }

    if (empty($_POST['price'])) {
      $priceError = 'Price Could Not Be Null';
    } elseif (empty($_POST['price']) && is_numeric($_POST['price']) != 1) {
      $priceError = 'Price Could Not Be Null';
    }
    if (empty($_FILES['image'])) {
      $imageError = 'Image is required';
    }

  } else {
    $file = 'image/' . ($_FILES['image']['name']);
    $imageType = pathinfo($file, PATHINFO_EXTENSION);

     if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png') {
      echo "<script>alert('Image should be jpg,jpeg,png');</script>";
    }else{ //image validation success
      $name = $_POST['name'];
      $desc = $_POST['description'];
      $category = $_POST['category'];
      $qty = $_POST['quantity'];
      $price = $_POST['price'];
      $image = $_FILES['image']['name'];

      move_uploaded_file($_FILES['image']['tmp_name'],$file);

      $stmt = $conn->prepare("INSERT INTO products(name,description,category_id,price,quantity,image)
       VALUES (:name,:description,:category,:price,:quantity,:image)");

      $result = $stmt->execute(
          array(':name'=>$name,':description'=>$desc,':category'=>$category,':price'=>$price,':quantity'=>$qty,':image'=>$image)
      );

      if ($result) {
        echo "<script>alert('Product is added');window.location.href='index.php';</script>";
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
            <form action="product_add.php" method="post" enctype="multipart/form-data">
              <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
              <div class="form-group">
                <label for="title">Nmae</label>
                <p style="color:red"><?php echo empty($nameError) ? '' : '*' . $nameError ?></p>
                <input type="text" name="name" id="nmae" class="form-control">
              </div>

              <div class="form-group">
                <label for="">Description</label>
                <p style="color:red"><?php echo empty($descriptionError) ? '' : '*' . $descriptionError ?></p>
                <textarea name="description" id="" class="form-control"></textarea>
              </div>
              <div class="form-group">
                <?php
                $catStmt = $conn->prepare("SELECT * FROM categories");
                $catStmt->execute();
                $catResult = $catStmt->fetchAll();
                ?>
                <label for="">Category</label>
                <p style="color:red"><?php echo empty($catError) ? '' : '*' . $catError ?></p>
                <select name="category" id="" class="form-control">
                  <option value="">Select Category</option>
                  <?php foreach ($catResult as $value) { ?>
                    <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label for="title">Quantity</label>
                <p style="color:red"><?php echo empty($qtyError) ? '' : '*' . $qtyError ?></p>
                <input type="number" name="quantity" id="nmae" class="form-control">
              </div>
              <div class="form-group">
                <label for="title">Price</label>
                <p style="color:red"><?php echo empty($priceError) ? '' : '*' . $priceError ?></p>
                <input type="number" name="price" id="nmae" class="form-control">
              </div>
              <div class="form-group">
                <label for="title">Image</label>
                <p style="color:red"></p>
                <input type="file" name="image" id="image" class="form-control">
              </div>
              <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-danger">
                <a href="index.php" class="btn btn-danger">Back</a>
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