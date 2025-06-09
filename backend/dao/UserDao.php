<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("users");
    }

    protected function getPrimaryKey() {
        return "UserID";
    }


       public function getOrders($id)
    {
        $sql = "SELECT * FROM orders WHERE UserID = :id";

        $statement = $this->connection->prepare($sql);

        $statement->bindParam(':id', $id);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getUserCart($UserID)
    {



        $sql = "SELECT c.CartID,
                       c.UserID,
                       c.price_total 

                 FROM cart c

                 JOIN cart_items ci ON c.CartID = ci.CartID

                 WHERE c.UserID = :UserID 

                 GROUP BY c.CartID, c.UserID;";


        $statement = $this->connection->prepare($sql);

        $statement->bindValue("UserID", $UserID);

        $statement->execute();

        return $statement->fetch();
    }



    public function getUserOrders($UserID)
    {   
        
        $sql = "SELECT * FROM cart_items ci JOIN webprojekat.cart c ON ci.CartID = c.CartID join products p on ci.ProductID = p.ProductID WHERE UserID = :UserID";



        $statement = $this->connection->prepare($sql);

        $statement->bindValue("UserID", $UserID);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function createCart($UserID)
    {
        $validUser = $this->getById($UserID);

        if ($validUser) {
            $sql = "INSERT INTO cart (UserID) VALUES (:UserID)";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue("UserID", $UserID);

            $statement->execute();

            if ($statement) {
                return ['success' => true, 'Message' => 'Created a cart successfully!'];
            }

            return ['success' => false, 'Message ' => 'User CANNOT have more than one cart!'];
        }



        return ['success' => false, 'Message ' => 'Invalid User ID!'];
    }




    public function checkOut($UserID)
    {
        $validUser = $this->getById($UserID);

        if (!$validUser) return ['Success' => 'False', 'Message ' => 'User is NOT valid!'];

        try {

            $statement = "UPDATE carts c SET c.status = 'ordered' WHERE UserID = :UserID";
            $statement = $this->connection->prepare($statement);
            $statement->bindValue(":UserID", $UserID);
            $statement->execute();

            if ($statement) {
                return ["Success: " => 'True', "Message: " => "Ordered item successfully!"];
            }
        } catch (PDOException $e) {
            return ['Success' => 'False', 'Message: ' => $e->getMessage()];
        }
    }

  
}
?>

