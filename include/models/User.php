<?php

class User
{
    public static function all(): array
    {
        return self::loadAll();
    }

    public static function findById(int $id): ?array
    {
        foreach (self::loadAll() as $user) {
            if ((int)$user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public static function findByEmail(string $email): ?array
    {
        foreach (self::loadAll() as $user) {
            if (!empty($user['email']) && mb_strtolower($user['email']) === mb_strtolower($email)) {
                return $user;
            }
        }
        return null;
    }

    public static function findByName(string $name): ?array
    {
        foreach (self::loadAll() as $user) {
            if (!empty($user['name']) && mb_strtolower($user['name']) === mb_strtolower($name)) {
                return $user;
            }
        }
        return null;
    }

    public static function existsByEmail(string $email, ?int $excludeId = null): bool
    {
        foreach (self::loadAll() as $user) {
            if ($excludeId !== null && (int)$user['id'] === $excludeId) {
                continue;
            }
            if (!empty($user['email']) && mb_strtolower($user['email']) === mb_strtolower($email)) {
                return true;
            }
        }
        return false;
    }

    public static function existsByName(string $name, ?int $excludeId = null): bool
    {
        foreach (self::loadAll() as $user) {
            if ($excludeId !== null && (int)$user['id'] === $excludeId) {
                continue;
            }
            if (!empty($user['name']) && mb_strtolower($user['name']) === mb_strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    public static function create(string $name, string $email, string $password): ?array
    {
        $users = self::loadAll();
        $newId = self::getNextId($users);
        $newUser = [
            'id' => $newId,
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'registered' => date('Y-m-d H:i:s'),
        ];

        $users[] = $newUser;
        return self::saveAll($users) ? $newUser : null;
    }

    public static function update(int $id, array $data): ?array
    {
        $users = self::loadAll();
        $updated = null;

        foreach ($users as $index => $user) {
            if ((int)$user['id'] !== $id) {
                continue;
            }

            if (isset($data['name'])) {
                $users[$index]['name'] = trim($data['name']);
            }

            if (isset($data['email'])) {
                $users[$index]['email'] = trim($data['email']);
            }

            if (isset($data['password'])) {
                $users[$index]['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $updated = $users[$index];
            break;
        }

        if ($updated === null) {
            return null;
        }

        return self::saveAll($users) ? $updated : null;
    }

    public static function delete(int $id): bool
    {
        $users = self::loadAll();
        $removed = false;

        foreach ($users as $index => $user) {
            if ((int)$user['id'] === $id) {
                unset($users[$index]);
                $removed = true;
                break;
            }
        }

        if (!$removed) {
            return false;
        }

        $users = array_values($users);
        return self::saveAll($users);
    }

    protected static function loadAll(): array
    {
        $path = self::getStoragePath();
        if (!file_exists($path)) {
            return self::loadDefaultUsers();
        }

        $json = @file_get_contents($path);
        if ($json === false) {
            return self::loadDefaultUsers();
        }

        $data = json_decode($json, true);
        if (!is_array($data)) {
            return self::loadDefaultUsers();
        }

        $users = [];
        foreach ($data as $key => $userData) {
            if (!is_array($userData) || !isset($userData['id'])) {
                continue;
            }

            if (is_string($key)) {
                $userData['name'] = $userData['name'] ?? $key;
            } else {
                $userData['name'] = !empty($userData['name']) ? $userData['name'] : ($userData['login'] ?? '');
            }

            $userData['email'] = $userData['email'] ?? '';
            $userData['password_hash'] = $userData['password_hash'] ?? '';
            $userData['registered'] = $userData['registered'] ?? date('Y-m-d H:i:s');
            $users[] = $userData;
        }

        return self::mergeDefaultUsers($users);
    }

    protected static function saveAll(array $users): bool
    {
        return @file_put_contents(self::getStoragePath(), json_encode(array_values($users), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    protected static function getStoragePath(): string
    {
        return __DIR__ . '/../../data/users.json';
    }

    protected static function loadDefaultUsers(): array
    {
        $defaultUsers = [];
        $configPath = __DIR__ . '/../config.php';
        if (file_exists($configPath)) {
            require_once $configPath;
            if (!empty($users) && is_array($users)) {
                foreach ($users as $login => $userData) {
                    $defaultUsers[] = [
                        'id' => $userData['id'] ?? 0,
                        'name' => $login,
                        'email' => '',
                        'password_hash' => $userData['password_hash'] ?? '',
                        'registered' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }
        return $defaultUsers;
    }

    protected static function mergeDefaultUsers(array $users): array
    {
        $existingNames = [];
        foreach ($users as $user) {
            if (!empty($user['name'])) {
                $existingNames[mb_strtolower($user['name'])] = true;
            }
            if (!empty($user['email'])) {
                $existingNames[mb_strtolower($user['email'])] = true;
            }
        }

        $defaultUsers = self::loadDefaultUsers();
        foreach ($defaultUsers as $defaultUser) {
            $name = mb_strtolower($defaultUser['name']);
            $email = mb_strtolower($defaultUser['email']);
            if (($name !== '' && !isset($existingNames[$name])) || ($email !== '' && !isset($existingNames[$email]))) {
                $users[] = $defaultUser;
            }
        }

        return array_values($users);
    }

    protected static function getNextId(array $users): int
    {
        if (empty($users)) {
            return 1;
        }

        $ids = array_map(fn($user) => (int)$user['id'], $users);
        return max($ids) + 1;
    }

    protected static function isAssoc(array $data): bool
    {
        return array_keys($data) !== range(0, count($data) - 1);
    }

    protected static function arrayKeysAreLogins(array $data): bool
    {
        foreach ($data as $key => $value) {
            if (!is_string($key)) {
                return false;
            }
            if (!is_array($value)) {
                return false;
            }
        }
        return true;
    }
}
