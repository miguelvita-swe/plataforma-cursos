<?php
/**
 * Auth helper functions
 *
 * Simple JSON-based user storage and session helpers.
 */

declare(strict_types=1);

session_start();

function get_users_file_path(): string
{
    return __DIR__ . '/data/users.json';
}

function load_users(): array
{
    $file = get_users_file_path();
    if (!file_exists($file)) {
        return [];
    }
    $contents = file_get_contents($file);
    if ($contents === false || trim($contents) === '') {
        return [];
    }
    $decoded = json_decode($contents, true);
    return is_array($decoded) ? $decoded : [];
}

function save_users(array $users): bool
{
    $file = get_users_file_path();
    $fp = fopen($file, 'c+');
    if (!$fp) return false;
    if (!flock($fp, LOCK_EX)) { fclose($fp); return false; }
    ftruncate($fp, 0);
    rewind($fp);
    $written = fwrite($fp, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return $written !== false;
}

function find_user_by_email(string $email): ?array
{
    $email = trim(strtolower($email));
    $users = load_users();
    foreach ($users as $u) {
        if (isset($u['email']) && strtolower($u['email']) === $email) {
            return $u;
        }
    }
    return null;
}

function get_user_by_id(string $id): ?array
{
    $users = load_users();
    foreach ($users as $u) {
        if (isset($u['id']) && $u['id'] === $id) return $u;
    }
    return null;
}

function create_user(string $name, string $email, string $password, string $bio = '', string $image = ''): ?array
{
    $email = trim(strtolower($email));
    if (find_user_by_email($email) !== null) return null;
    $users = load_users();
    $id = uniqid('user_', true);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $user = [
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'password' => $hash,
        'bio' => $bio,
        'image' => $image,
        'created_at' => date('c')
    ];
    $users[] = $user;
    if (!save_users($users)) return null;
    return $user;
}

function update_user(string $id, array $data): ?array
{
    $users = load_users();
    $found = false;
    foreach ($users as &$u) {
        if (isset($u['id']) && $u['id'] === $id) {
            $found = true;
            $u = array_merge($u, $data);
            // Prevent accidental password overwrite with empty
            if (isset($data['password']) && $data['password'] !== '') {
                $u['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            break;
        }
    }
    unset($u);
    if (!$found) return null;
    if (!save_users($users)) return null;
    return get_user_by_id($id);
}

function verify_user_credentials(string $email, string $password): ?array
{
    $u = find_user_by_email($email);
    if (!$u) return null;
    if (!isset($u['password'])) return null;
    if (password_verify($password, $u['password'])) {
        return $u;
    }
    return null;
}

function auth_login_user(array $user): void
{
    // regenerate session id
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'image' => $user['image'] ?? ''
    ];
}

function auth_logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function auth_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_auth(): void
{
    if (!auth_user()) {
        // redirect to login with return url
        $return = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: /login.php?return=' . urlencode($return));
        exit;
    }
}

?>