<?php
$pageTitle = "Add Product";
include('../Config/db_connect.php');

abstract class Product
{    
    protected $sku;
    protected $name;
    protected $price;
    protected $extra_fields;

    public function __construct($sku, $name, $price, $extra_fields = []) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->extra_fields = $extra_fields;
    }

    public function db_save() {
        global $conn;
        $sql = $this->getInsertSql();

        if(mysqli_query($conn, $sql)) {
            header('Location: index.php');
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }

    protected function getInsertSql() {
        $extra_fields = json_encode($this->extra_fields);

        $base_sql = "INSERT INTO products(sku, name, price, extra_values)";
        $values_sql = " VALUES ('$this->sku', '$this->name', '$this->price', '$extra_fields')";

        return $base_sql . $values_sql;
    }
}

class DVD extends Product {

    public function __construct($sku, $name, $price, $size, $extra_fields =[]) {
        parent::__construct($sku, $name, $price, $extra_fields);
        $this->extra_fields['Size(MB)'] = $size;
    }
}

class book extends Product {

    public function __construct($sku, $name, $price, $weight, $extra_fields =[]) {
        parent::__construct($sku, $name, $price, $extra_fields);
        $this->extra_fields['Weight(KG)'] = $weight;
    }
}

class furniture extends Product {

    public function __construct($sku, $name, $price, $height, $width, $length, $extra_fields =[]) {
        parent::__construct($sku, $name, $price, $extra_fields);
        $this->extra_fields['Dimension(CM)'] = $height . 'X' . $width . 'X' . $length;
        print_r($extra_fields);
    }
}

$error = '';
if (isset($_POST['submit'])) {

    // check form
    if(!empty($_POST['sku'])){
        if (!preg_match('/^[A-Z0-9]+$/',$_POST['sku'])) {
            $error = 'SKU needs to be uppercase letters and numbers';
        }
    // check if SKU is unique
        $sku = $_POST['sku'];
        $sql = "SELECT COUNT(*) as count FROM products WHERE sku='$sku'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] != 0) {
            $error = 'SKU needs to be unique';
        }
    }

    if($error === '') {
        // create the product object
        $inputs = array();
        foreach ($_POST as $key => $value) {
            if($value != NULL) {
                $inputs[$key] = $value;
            }
        }
        unset($inputs['sku']);
        unset($inputs['name']);
        unset($inputs['price']);
        unset($inputs['type']);
        unset($inputs['submit']);

        $product = new $_POST['type']($_POST['sku'], $_POST['name'], $_POST['price'], ...$inputs);

        // save product to database
        $product->db_save();
    }

}
?>

<!DOCTYPE html>
<html>
<?php include('../Templates/header.php'); ?>
<nav>
  <h3>Add Product</h3>
  <a id="cancel" class="button" href="index.php">CANCEL</a>
</nav>

<section id="addContainer">
    <form id="product_form" action="add.php" method="POST">
        <div class="row">
            <label>SKU:</label>
            <input id="sku" type="text" name="sku" required>
            <p><?php echo $error ?></p>
        </div>
        <div class="row">
            <label>Name:</label>
            <input id="name" type="text" name="name" required>
        </div>
        <div class="row">
            <label>Price($):</label>
            <input id="price" type="number" name="price" required>
        </div>
        <div class="row">
            <label>Product Type:</label>
            <select id="productType" name="type">
                <option id="DVD" value="DVD" selected>DVD</option>
                <option id="Book" value="Book">Book</option>
                <option id="Furniture" value="Furniture">Furniture</option>
            </select>
        </div>
        <section id="typeSpace">
            <div class="typeField DVDField">
                <div class="row">
                    <label>Size(MB):</label>
                    <input id="size" type="number" name="size" required>
                </div>
                <p class="description">Please, provide size</p>
            </div>
            <div class="typeField BookField">
                <div class="row">
                    <label>weight(KG):</label>
                    <input id="weight" type="number" name="weight">
                </div>
                <p class="description">Please, provide weigth</p>
            </div>
            <div class="typeField FurnitureField">
                <div class="row">
                    <label>Height(CM):</label>
                    <input id="height" type="number" name="height">
                </div>
                <div class="row">
                    <label>Width(CM):</label>
                    <input id="width" type="number" name="width">
                </div>
                <div class="row">
                    <label>Length(CM):</label>
                    <input id="length" type="number" name="length">
                </div>
                <p class="description">Please, provide dimensions</p>
            </div>
        </section>
        <input id="submit" type="submit" name="submit" value="SAVE">
    </form>
</section>

<script type="text/javascript" src="../script.js"></script>
<?php include('../templates/footer.php'); ?>
</html>