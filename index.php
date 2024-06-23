<?php
session_start(); // Запуск сесії

// Визначення класу Product
class Product {
    private $name;
    private $price;

    public function __construct($name, $price) 
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function getPrice() 
    {
        return $this->price;
    }

    public function getProduct() 
    {
        return "Name: " . $this->name . "; price: " . $this->price;
    }

    // Метод для пошуку продукту за назвою
    public static function searchByName($products, $name) 
    {
        foreach ($products as $product) 
        {
            if (strcasecmp($product->getName(), $name) == 0)
            {
                return $product;
            }
        }
        return null;
    }

}

// Ініціалізація масиву продуктів з сесії
if (!isset($_SESSION['products'])) 
{
    $_SESSION['products'] = [];
}

// Обробка форми
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addProduct'])) 
{
    $name = $_POST['productName'];
    $price = $_POST['productPrice'];

    // Створення нового об'єкта Product та додавання його до масиву сесії
    $newProduct = new Product($name, $price);
    $_SESSION['products'][] = $newProduct;

}

// Обробка форми для пошуку продукту
$searchResult = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchProduct'])) 
{
    $searchName = $_POST['searchName'];
    $searchResult = Product::searchByName($_SESSION['products'], $searchName);
}

$products = $_SESSION['products'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
</head>
<body>
    <h1>Product Management</h1>

    <!-- Форма для додавання нового продукту -->
    <form method="post" action="">
        <input type="text" name="productName" placeholder="Product Name" required>
        <input type="number" name="productPrice" placeholder="Product Price" step="0.01" required>
        <button type="submit" name="addProduct">Add Product</button>
    </form>

    <h2>Product List</h2>
    <ul>
        <?php
        foreach ($products as $product) 
        {
            echo "<li>" . $product->getProduct() . "</li>";
        }
        ?>
    </ul>

    <!-- Форма для пошуку -->
    <form method="post" action="">
        <input type="text" name="searchName" placeholder="Product Name" required>
        <button type="submit" name="searchProduct">Search</button>
    </form>

    <?php
    // Відображення
    if ($searchResult) {
        echo "<h3>Search Result:</h3>";
        echo "<p>" . $searchResult->getProduct() . "</p>";
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchProduct'])) 
    {
        echo "<p>No product found with that name.</p>";
    }
    ?>
    

</body>
</html>
