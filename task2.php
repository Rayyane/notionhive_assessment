<?php
    require_once("connection.php");
    require_once("category.php");

    $database = new Database();

    $rootCategories = Category::getRootCategories($database);

    if (!empty($rootCategories)) {
        echo "<h2>Category Tree with Item Counts</h2>";
        foreach ($rootCategories as $rootCategory) {
            $rootCategory->getCategoryTreeWithItems($database);
            displayCategoryTree([$rootCategory]);
        }
    } else {
        echo "There are no root categories in the database.";
    }

    function displayCategoryTree($categories, $level = 0) {
        foreach ($categories as $category) {
            echo str_repeat("-", $level * 2) . $category->getName() . " (" . $category->getTotalItems() . ")" . "<br>";
            $children = $category->getChildren();
            if (!empty($children)) {
                displayCategoryTree($children, $level + 1);
            }
        }
    }

    $database->close();




