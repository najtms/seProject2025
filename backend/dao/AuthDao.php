<?php


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthDao extends BaseDao
{

    protected $table = 'users';


    public function __construct($table = 'users')
    {
        parent::__construct($table);
    }


    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE Email = :email";
        $statement = $this->connection->prepare($sql);

        $statement->bindParam(':email', $email);

        $statement->execute();

        return $statement->fetch();
    }

 public function register($entity)
{
  

    if (empty($entity['Email']) || empty($entity['Password'])) {
        return ['success' => false, 'error' => 'Email and password are required.'];
    }

    if (!filter_var($entity['Email'], FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Invalid Email Address!!!'];
    }

    $email_exists = $this->getUserByEmail($entity['Email']);

    if ($email_exists) {
        echo "Email already exists\n";
        return ['success' => false, 'error' => 'Email already registered.'];
    }

    $entity['Password'] = password_hash($entity['Password'], PASSWORD_BCRYPT);

    $insertResult = parent::insert($entity); // store result separately

    unset($entity['Password']); // now $entity is still your array

    return true;
}



    public static function validateEntity($entity, $fields)
    {
        $missingFields = [];

        foreach ($fields as $field) {
            if (!isset($entity[$field]) || trim($entity[$field]) === '') {
                $missingFields[] = ucfirst($field);
            }
        }

        if (!empty($missingFields)) {
            return [
                'Success' => FALSE,
                'Error' => 'The following fields are required: ' . implode(', ', $missingFields)
            ];
        }

        return ['Success' => TRUE, 'Message' => 'Entity is valid'];
    }



    public function login($entity)
    {   

   

        if (empty($entity['Email']) || empty($entity['Password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->getUserByEmail($entity['Email']);
        if (!$user) {
            return ['success' => false, 'error' => 'User Already Exists!!!.'];
        }

        if (!$user || !password_verify($entity['Password'], $user['Password']))
            return ['success' => false, 'error' => 'Invalid123123123 username or password.'];

        unset($user['Password']);

        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24)
        ];

        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        return ['success' => true, 'data' => array_merge($user, ['user_token' => $token])];
    }
}