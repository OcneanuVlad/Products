<?php

include('Config/db_connect.php');

$pageTitle = "Products";

// get data from database
$result = mysqli_query($conn, 'SELECT * FROM DVDs ORDER BY SKU');
$dvds = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
$result = mysqli_query($conn, 'SELECT * FROM books ORDER BY SKU');
$books = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
$result = mysqli_query($conn, 'SELECT * FROM furniture ORDER BY SKU');
$furniture = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

if(isset($_POST['delete'])) {
    $idsToDelete = $_POST['check'];
    foreach ($idsToDelete as $id) {
        $idd = mysqli_real_escape_string($conn, $id);
        $sql = "DELETE FROM `dvds` WHERE `dvds`.`SKU` = '$idd'";
        if (mysqli_query($conn, $sql)) {
            header('Location:index.php');
        } else {
            echo 'query error: ' . mysqli.error($conn);
        }
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<?php include('Templates/header.php'); ?>
<form action="index.php" method="POST">

    <div id='productsContainer'>
        <?php foreach ($dvds as $dvd): ?>
            <div class='product'>
                <input type="checkbox" class="check" name="check[]" value="<?php echo $dvd['SKU']?>">
                <p><?php echo htmlspecialchars($dvd['SKU'])?></p>
                <p><?php echo htmlspecialchars($dvd['Name'])?></p>
                <p><?php echo htmlspecialchars($dvd['Price'])?> $</p>
                <p>Size: <?php echo htmlspecialchars($dvd['Size'])?> MB</p>
            </div>
        <?php endforeach ?>
        <?php foreach ($books as $book): ?>
            <div class='product'>
                <input type="checkbox" class="check" name="check[]" value="<?php echo $book['SKU']?>">
                <p><?php echo htmlspecialchars($book['SKU'])?></p>
                <p><?php echo htmlspecialchars($book['Name'])?></p>
                <p><?php echo htmlspecialchars($book['Price'])?> $</p>
                <p>Weight: <?php echo htmlspecialchars($book['Weight'])?> KG</p>
            </div>
        <?php endforeach ?>
        <?php foreach ($furniture as $piece): ?>
            <div class='product'>
                <input type="checkbox" class="check" name="check[]" value="<?php echo $piece['SKU']?>">
                <p><?php echo htmlspecialchars($piece['SKU'])?></p>
                <p><?php echo htmlspecialchars($piece['Name'])?></p>
                <p><?php echo htmlspecialchars($piece['Price'])?> $</p>
                <p>Dimension: <?php echo htmlspecialchars($piece['Height'],'X',$piece['Width'],'X',$piece['Length'])?></p>
            </div>
        <?php endforeach ?>
    </div>

    <nav>
        <h3>Product List</h3>
        <a class="button" href="add.php">ADD</a>
        <input class="button" id="delete" type="submit" name="delete" value="MASS-DELETE">
    </nav>
</form>


<?php include('Templates/footer.php'); ?>
</html>