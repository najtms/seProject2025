<?php
require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . '/../dao/AuthDao.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService
{
    private $auth_dao;
    public function __construct()
    {
        $this->auth_dao = new AuthDao();
        parent::__construct(new AuthDao);
    }

    public function get_user_by_email($email)
    {
        return $this->auth_dao->get_user_by_email($email);
    }


    public function get_role_by_name($name)
    {
        return $this->auth_dao->get_role_by_name($name);
    }
    public function register($entity)
    {
        if (empty($entity['Name']) || empty($entity['email']) || empty($entity['password']) || empty($entity['RoleID'])) {
            return ['success' => false, 'error' => 'Username, email, password, and Role are required.'];
        }

        $valid_roles = [1, 2]; // 1 = Admin, 2 = User
        if (!in_array($entity['RoleID'], $valid_roles)) {
            return ['success' => false, 'error' => 'Invalid role selected.'];
        }

        $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
        if ($email_exists) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);
        unset($entity['password']);
        return ['success' => true, 'data' => $entity];
    }

    public function login($entity)
    {
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if (!$user) {
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }

        if (!$user || !password_verify($entity['password'], $user['Password']))
            return ['success' => false,  'error' => 'Invalid username or password.'];

        unset($user['Password']);

        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // Token valid for 24 hours
            'role' => $user['RoleID'],
        ];

        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
    }
}
