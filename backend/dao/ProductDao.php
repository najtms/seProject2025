<?php
require_once 'BaseDao.php';

class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct("products");
    }

    protected function getPrimaryKey() {
        return "ProductID";
    }

    public function deleteProduct($productId) {
        $sql = "DELETE FROM products WHERE ProductID = :id";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $productId);
        if ($stmt->execute()) {
            return ['Success' => true, 'Message' => 'Product deleted successfully.'];
        } else {
            return ['Success' => false, 'Message' => 'Failed to delete product.'];
        }

    }

    public function getOnSale() {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE OnSale = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategoryId($categoryId) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE CategoryID = :categoryId");
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByName($keyword) {
        $searchTerm = '%' . $keyword . '%';
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE Name LIKE :keyword");
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStock($productId, $newStock) {
        $stmt = $this->connection->prepare("UPDATE products SET Stock = :stock WHERE ProductID = :id");
        $stmt->bindParam(':stock', $newStock);
        $stmt->bindParam(':id', $productId);
        return $stmt->execute();
    }

    public function addProduct($data) {
        try {
            // Validate required fields
            $required = ['Name', 'Price', 'Stock', 'CategoryID', 'Description', 'ImageURL'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || $data[$field] === '') {
                    throw new Exception("Missing required field: $field");
                }
            }

            // Validate data types and constraints
            if (!is_numeric($data['Price']) || $data['Price'] <= 0) {
                throw new Exception("Price must be a positive number");
            }
            if (!is_numeric($data['Stock']) || $data['Stock'] < 0) {
                throw new Exception("Stock must be a non-negative number");
            }
            if (!is_numeric($data['CategoryID'])) {
                throw new Exception("Invalid CategoryID");
            }

            // Set default values for optional fields
            if (!isset($data['OnSale'])) {
                $data['OnSale'] = 0;
            }

            // Build dynamic SQL query
            $columns = array_keys($data);
            $placeholders = array_map(function($col) { return ":$col"; }, $columns);
            $sql = "INSERT INTO products (" . implode(", ", $columns) . ") 
                   VALUES (" . implode(", ", $placeholders) . ")";

            $stmt = $this->connection->prepare($sql);

            // Bind all parameters dynamically
            foreach ($data as $key => $value) {
                // Determine parameter type
                $type = PDO::PARAM_STR;
                if (is_int($value) || in_array($key, ['Stock', 'CategoryID', 'OnSale'])) {
                    $type = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                } elseif (is_float($value) || $key === 'Price') {
                    $value = (string)$value; // Convert float to string for PDO
                }
                $stmt->bindValue(":$key", $value, $type);
            }

            // Execute the query
            if ($stmt->execute()) {
                $insertId = $this->connection->lastInsertId();
                return [
                    'success' => true,
                    'message' => 'Product added successfully',
                    'productId' => $insertId,
                    'data' => $data
                ];
            } else {
                throw new Exception("Failed to execute query");
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    public function getPaged($limit, $offset) {
        $stmt = $this->connection->prepare("SELECT * FROM products LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getAll($order_column = null, $order_direction = 'ASC') {
        // If you need custom product ordering logic, put it here
        // Otherwise just call the parent implementation
        return parent::getAll($order_column, $order_direction);
    }

    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE ProductID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $productData) {
        $fields = [];
        foreach ($productData as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE ProductID = :id";
        $stmt = $this->connection->prepare($sql);
        foreach ($productData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id);
        if ($stmt->execute()) {
            return ['Success' => true, 'Message' => 'Product updated successfully.'];
        } else {
            return ['Success' => false, 'Message' => 'Failed to update product.'];
        }
    }
}
?>
