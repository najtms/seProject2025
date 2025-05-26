<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PaymentDao.php';

class PaymentService extends BaseService {
    public function __construct() {
        parent::__construct(new PaymentDao());
    }

    public function processPayment($orderId, $userId, $amount, $method) {
        if ($amount <= 0) throw new Exception("Invalid payment amount");
        if (!in_array($method, ['credit_card', 'paypal', 'bank_transfer'])) {
            throw new Exception("Invalid payment method");
        }
        
        return $this->dao->insert([
            'OrderID' => $orderId,
            'UserID' => $userId,
            'Amount' => $amount,
            'PaymentMethod' => $method,
            'Status' => 'completed'
        ]);
    }
}
?>