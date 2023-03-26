<?php

include('../Config/db_connect.php');

$pageTitle = "Products";

// get data from database
$result = mysqli_query($conn, 'SELECT * FROM products ORDER BY sku');
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

$error = '';

// delete products
if(isset($_POST['delete'])) {
    if(!empty($_POST['check'])) {
        $idsToDelete = $_POST['check'];
        foreach ($idsToDelete as $id) {
            $idd = mysqli_real_escape_string($conn, $id);
            $sql = "DELETE FROM `products` WHERE `products`.`sku` = '$idd'";
            if (mysqli_query($conn, $sql)) {
                header('Location:index.php');
            } else {
                echo 'query error: ' . mysqli.error($conn);
            }
        }
    } else {
        $error = 'Select products to delete';
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<?php include('../Templates/header.php'); ?>
<form id="form" action="index.php" method="POST">

    <div id='productsContainer'>
        <?php foreach ($products as $product): ?>
            <div class='product'>
                <input type="checkbox" class="delete-checkbox" name="check[]" value="<?php echo $product['sku']?>">
                <p><?php echo htmlspecialchars($product['sku'])?></p>
                <p><?php echo htmlspecialchars($product['name'])?></p>
                <p><?php echo htmlspecialchars($product['price'])?> $</p>
                <?php $extra_fields = json_decode($product['extra_values']);
                    foreach ($extra_fields as $key => $value): ?>
                        <p><?php echo htmlspecialchars($key) . ': ' . htmlspecialchars($value) ?></p>
                <?php endforeach ?>
            </div>
        <?php endforeach ?>
    </div>

    <nav>
        <h3>Product List</h3>
        <a class="button" href="add.php">ADD</a>
        <input class="button" id="delete-product-btn" type="submit" name="delete" value="MASS DELETE">
        <p id="deleteError"><?php echo $error?></p>
    </nav>
</form>


<?php include('../Templates/footer.php'); ?>
</html>