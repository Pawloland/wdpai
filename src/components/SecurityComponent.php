<?php
require_once __DIR__ . '/../repository/SessionRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityComponent
{
    protected const int days = 0;
    protected const int hours = 0;
    protected const int minutes = 90;

    protected const string cookie_name = 'auth';
    protected const string cookie_name_admin = 'auth_admin';

    protected SessionRepository $sessionRepository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->sessionRepository = new SessionRepository();
        $this->userRepository = new UserRepository();
    }

    public function getMail(): ?string
    {
        if (!isset($_COOKIE[static::cookie_name])
            or !($auth = json_decode($_COOKIE[static::cookie_name], true))
            or !isset($auth['email'])) {
            return null;
        }
        return $auth['email'];
    }

    public function getNick(): ?string
    {
        if (!isset($_COOKIE[static::cookie_name_admin])
            or !($auth = json_decode($_COOKIE[static::cookie_name_admin], true))
            or !isset($auth['nick'])) {
            return null;
        }
        return $auth['nick'];
    }

    public function createAuthCookie(string $email): void
    {
        $expiration_date = '';

        $token = $this->sessionRepository->createSession($email, static::days, static::hours, static::minutes, $expiration_date);

        $cookieValue = json_encode(['token' => $token, 'email' => $email]);
        setcookie(static::cookie_name, $cookieValue, [
            'expires' => strtotime($expiration_date . ' UTC'),
            'path' => '/',
            'samesite' => 'Strict',
            'secure' => true,
            'httponly' => true
        ]);
        // Force update $_COOKIE for current execution scope (to render returned view populated with data correctly)
        $_COOKIE[static::cookie_name] = $cookieValue;
    }

    public function createAdminAuthCookie(string $nick): void
    {
        $expiration_date = '';

        $token = $this->sessionRepository->createUserSession($nick, static::days, static::hours, static::minutes, $expiration_date);

        $cookieValue = json_encode(['token' => $token, 'nick' => $nick]);
        setcookie(static::cookie_name_admin, $cookieValue, [
            'expires' => strtotime($expiration_date . ' UTC'),
            'path' => '/',
            'samesite' => 'Strict',
            'secure' => true,
            'httponly' => true
        ]);
        // Force update $_COOKIE for current execution scope (to render returned view populated with data correctly)
        $_COOKIE[static::cookie_name_admin] = $cookieValue;
    }


    public function updateAuthCookie(): bool
    {
        if (!isset($_COOKIE[static::cookie_name])
            or !($auth = json_decode($_COOKIE[static::cookie_name], true))
            or !isset($auth['token'])
            or !isset($auth['email'])) {
            return false;
        }
        $expiration_date = '';
        try {
            $is_valid = $this->sessionRepository->checkSession($auth['email'], $auth['token'], static::days, static::hours, static::minutes, $expiration_date);
        } catch (Exception $e) {
            $is_valid = false;
        }

        $cookieValue = json_encode(['token' => $auth['token'], 'email' => $auth['email']]);
        setcookie(static::cookie_name, $cookieValue, [
            'expires' => $is_valid ? strtotime($expiration_date . ' UTC') : 1,
            'path' => '/',
            'samesite' => 'Strict',
            'secure' => true,
            'httponly' => true
        ]);
        // Force update $_COOKIE for current execution scope (to render returned view populated with data correctly)
        $_COOKIE[static::cookie_name] = $cookieValue;
        return $is_valid;
    }

    public function updateAdminAuthCookie(): bool
    {
        if (!isset($_COOKIE[static::cookie_name_admin])
            or !($auth = json_decode($_COOKIE[static::cookie_name_admin], true))
            or !isset($auth['token'])
            or !isset($auth['nick'])) {
            return false;
        }
        $expiration_date = '';
        try {
            $is_valid = $this->sessionRepository->checkUserSession($auth['nick'], $auth['token'], static::days, static::hours, static::minutes, $expiration_date);
        } catch (Exception $e) {
            $is_valid = false;
        }

        $cookieValue = json_encode(['token' => $auth['token'], 'nick' => $auth['nick']]);
        setcookie(static::cookie_name_admin, $cookieValue, [
            'expires' => $is_valid ? strtotime($expiration_date . ' UTC') : 1,
            'path' => '/',
            'samesite' => 'Strict',
            'secure' => true,
            'httponly' => true
        ]);
        // Force update $_COOKIE for current execution scope (to render returned view populated with data correctly)
        $_COOKIE[static::cookie_name_admin] = $cookieValue;
        return $is_valid;
    }


    public function destroyAuthCookie(): void
    {
        try {
            $auth = json_decode($_COOKIE[static::cookie_name], true);
            $this->sessionRepository->deleteSession($auth['email'], $auth['token']);
        } catch (Exception $e) {
        }
        // Purge the cookie by setting its value to an empty string and its expiration date to a time in the past
        setcookie(static::cookie_name, '', [
            'expires' => 1,
            'path' => '/',
            'samesite' => 'Strict', // Changed to 'Strict'
            'secure' => true, // if your site is served over HTTPS
            'httponly' => true
        ]);
        // Force delete from $_COOKIE for current execution scope (to render returned view populated with data correctly)
        unset($_COOKIE[static::cookie_name]);
    }

    public function destroyAdminAuthCookie(): void
    {
        try {
            $auth = json_decode($_COOKIE[static::cookie_name_admin], true);
            $this->sessionRepository->deleteUserSession($auth['nick'], $auth['token']);
        } catch (Exception $e) {
        }
        // Purge the cookie by setting its value to an empty string and its expiration date to a time in the past
        setcookie(static::cookie_name_admin, '', [
            'expires' => 1,
            'path' => '/',
            'samesite' => 'Strict', // Changed to 'Strict'
            'secure' => true, // if your site is served over HTTPS
            'httponly' => true
        ]);
        // Force delete from $_COOKIE for current execution scope (to render returned view populated with data correctly)
        unset($_COOKIE[static::cookie_name_admin]);
    }

    public function checkPermission(string $perm_name): bool
    {
        //Assume authentication is already checked and the auth_admin cookie is set
        $auth_admin = json_decode($_COOKIE[static::cookie_name_admin], true);
        $nick = $auth_admin['nick'];
        return $this->userRepository->checkPermission($nick, $perm_name);
    }


}