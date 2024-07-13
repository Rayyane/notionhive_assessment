<?php
class Category {
    private $id;
    private $name;
    private $parentId;
    private $totalItems;
    private $children;

    public function __construct($id, $name, $parentId = null, $totalItems = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
        $this->totalItems = $totalItems;
        $this->children = [];
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function getTotalItems() {
        return $this->totalItems;
    }

    public function getChildren() {
        return $this->children;
    }

    public static function getCategoriesWithTotalItems(Database $db) {
        $sql = "SELECT c.id AS category_id, c.Name AS category_name, COUNT(icr.ItemNumber) AS total_items
                FROM category c
                LEFT JOIN item_category_relations icr ON c.id = icr.categoryId
                GROUP BY c.id, c.Name
                ORDER BY total_items DESC";
        $result = $db->query($sql);
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $categories[] = new Category($row['category_id'], $row['category_name'], null, $row['total_items']);
        }
        return $categories;
    }

    public function getCategoryTreeWithItems(Database $db) {
        $sql = "SELECT c.id AS category_id, c.Name AS category_name
                FROM category c
                LEFT JOIN catetory_relations cr ON c.id = cr.categoryId
                WHERE cr.ParentcategoryId = " . $this->id;
        $result = $db->query($sql);
    
        while ($row = mysqli_fetch_assoc($result)) {
          $child = new Category($row['category_id'], $row['category_name']);
          $child->getCategoryTreeWithItems($db);
          $this->children[] = $child;
        }
    
        $this->totalItems = $this->calculateTotalItems($db);
    }
    
    private function calculateTotalItems(Database $db) {
        $sql = "SELECT COUNT(icr.itemNumber) AS total_items
                FROM item_category_relations icr
                WHERE icr.categoryId = " . $this->id;
        $result = $db->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalItems = $row['total_items'] ?? 0;
        foreach ($this->children as $child) {
          $totalItems += $child->calculateTotalItems($db);
        }
        return $totalItems;
    }
}