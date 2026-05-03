<?php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private static function send(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private static function sanitize(array $user): array
    {
        unset($user['password_hash']);
        return $user;
    }

    public static function register(array $data): void
    {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if ($name === '') {
            self::send(['status' => 'error', 'message' => 'Name is required'], 400);
        }

        if ($email === '') {
            self::send(['status' => 'error', 'message' => 'Email is required'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::send(['status' => 'error', 'message' => 'Email is invalid'], 400);
        }

        if ($password === '' || mb_strlen($password) < 6) {
            self::send(['status' => 'error', 'message' => 'Password must be at least 6 characters'], 400);
        }

        if (User::existsByEmail($email)) {
            self::send(['status' => 'error', 'message' => 'User with this email already exists'], 409);
        }

        if (User::existsByName($name)) {
            self::send(['status' => 'error', 'message' => 'User with this name already exists'], 409);
        }

        $user = User::create($name, $email, $password);
        if (!$user) {
            self::send(['status' => 'error', 'message' => 'Unable to register user'], 500);
        }

        self::send([
            'status' => 'success',
            'message' => 'User registered',
            'user' => self::sanitize($user),
        ], 201);
    }

    public static function login(array $data): void
    {
        $email = trim($data['email'] ?? '');
        $name = trim($data['name'] ?? '');
        $password = $data['password'] ?? '';

        if ($email === '' && $name === '') {
            self::send(['status' => 'error', 'message' => 'Email or name is required'], 400);
        }

        if ($password === '') {
            self::send(['status' => 'error', 'message' => 'Password is required'], 400);
        }

        $user = null;
        if ($email !== '') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                self::send(['status' => 'error', 'message' => 'Email is invalid'], 400);
            }
            $user = User::findByEmail($email);
        }

        if (!$user && $name !== '') {
            $user = User::findByName($name);
        }

        if (!$user || !password_verify($password, $user['password_hash'])) {
            self::send(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        self::send([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => self::sanitize($user),
        ]);
    }

    public static function index(): void
    {
        $users = array_map([self::class, 'sanitize'], User::all());
        self::send(['status' => 'success', 'data' => $users]);
    }

    public static function show(int $id): void
    {
        $user = User::findById($id);
        if (!$user) {
            self::send(['status' => 'error', 'message' => 'User not found'], 404);
        }
        self::send(['status' => 'success', 'data' => self::sanitize($user)]);
    }

    public static function update(int $id, array $data): void
    {
        $user = User::findById($id);
        if (!$user) {
            self::send(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $update = [];
        if (isset($data['password'])) {
            $password = $data['password'];
            if ($password === '' || mb_strlen($password) < 6) {
                self::send(['status' => 'error', 'message' => 'Password must be at least 6 characters'], 400);
            }
            $update['password'] = $password;
        }

        if (isset($data['email'])) {
            $email = trim($data['email']);
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                self::send(['status' => 'error', 'message' => 'Email is invalid'], 400);
            }
            if (User::existsByEmail($email, $id)) {
                self::send(['status' => 'error', 'message' => 'Email already in use'], 409);
            }
            $update['email'] = $email;
        }

        if (isset($data['name'])) {
            $name = trim($data['name']);
            if ($name === '') {
                self::send(['status' => 'error', 'message' => 'Name cannot be empty'], 400);
            }
            if (User::existsByName($name, $id)) {
                self::send(['status' => 'error', 'message' => 'Name already in use'], 409);
            }
            $update['name'] = $name;
        }

        if (empty($update)) {
            self::send(['status' => 'error', 'message' => 'No valid data provided for update'], 400);
        }

        $updatedUser = User::update($id, $update);
        if (!$updatedUser) {
            self::send(['status' => 'error', 'message' => 'Unable to update user'], 500);
        }

        self::send([
            'status' => 'success',
            'message' => 'User updated',
            'user' => self::sanitize($updatedUser),
        ]);
    }

    public static function destroy(int $id): void
    {
        if (!User::findById($id)) {
            self::send(['status' => 'error', 'message' => 'User not found'], 404);
        }

        if (!User::delete($id)) {
            self::send(['status' => 'error', 'message' => 'Unable to delete user'], 500);
        }

        self::send(['status' => 'success', 'message' => 'User deleted']);
    }
}
