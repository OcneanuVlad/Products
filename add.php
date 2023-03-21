<?php
include('Config/db_connect.php');

$pageTitle = "Add Product";
$error = '';
if (isset($_POST['submit'])) {

    // check form
    if(!empty($_POST['SKU'])){
        if (!preg_match('/^[A-Z0-9]+$/',$_POST['SKU'])) {
            $error = 'SKU needs to be uppercase letters and numbers';
        }
    }

    if($error === '') {
        // save data to database
        $sku = mysqli_real_escape_string($conn, $_POST['SKU']);
        $name = mysqli_real_escape_string($conn, $_POST['Name']);
        $price = mysqli_real_escape_string($conn, $_POST['Price']);

        $sql = "INSERT INTO DVDs(SKU,Name,Price) VALUES('$sku', '$name', '$price')";

        if(mysqli_query($conn, $sql)) {
            header('Location: index.php');
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }

}
?>

<!DOCTYPE html>
<html>
<?php include('Templates/header.php'); ?>
<nav>
  <h3>Add Product</h3>
  <a class="button" href="index.php">CANCEL</a class="button">
</nav>

<section id="container">
    <form id="product_form" action="add.php" method="POST">
        <div class="row">
            <label id="sku">SKU:</label>
            <input type="text" name="SKU" required>
            <p><?php echo $error ?></p>
        </div>
        <div class="row">
            <label id="name">Name:</label>
            <input type="text" name="Name" required>
        </div>
        <div class="row">
            <label for="Price" id="price">Price($):</label>
            <input type="number" name="Price" required>
        </div>
        <div class="row">
            <label>Product Type:</label>
            <select name="Type">
                <option value="DVD" selected>DVD</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
            </select>
        </div>
        <section>

        </section>
        <input id="submit" type="submit" name="submit" value="SAVE">
    </form>
</section>

<?php include('templates/footer.php'); ?>
</html>