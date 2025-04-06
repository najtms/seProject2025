<?php
require_once 'BaseDao.php';

class PaymentDao extends BaseDao {
    public function __construct() {
        parent::__construct("payments");
    }

    protected function getPrimaryKey() {
        return "PaymentID";
    }
}
?>
