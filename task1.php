<?php
    require_once("connection.php");
    require_once("category.php");

    $database = new Database();
    $categories = Category::getCategoriesWithTotalItems($database);

    echo "<h2>All Categories with Total Items</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Category ID</th><th>Category Name</th><th>Total Items</th></tr>";

    foreach ($categories as $category) {
        echo "<tr>";
        echo "<td>" . $category->getId() . "</td>";
        echo "<td>" . $category->getName() . "</td>";
        echo "<td>" . $category->getTotalItems() . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    $database->close();