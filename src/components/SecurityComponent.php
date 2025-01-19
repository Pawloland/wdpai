<?php
require_once __DIR__ . '/../repository/SessionRepository.php';

class SecurityComponent
{
    protected const int days = 0;
    protected const int hours = 0;
    protected const int minutes = 10;

    protected const string cookie_name = 'auth';

    protected SessionRepository $sessionRepository;

    public function __construct()
    {
        $this->sessionRepository = new SessionRepository();
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

    public function updateAuthCookie(): bool
    {
        if (!isset($_COOKIE[static::cookie_name])
            or !($auth = json_decode($_COOKIE[static::cookie_name], true))
            or !isset($auth['token'])
            or !isset($auth['email'])) {
            return false;
        }
        $expiration_date = '';
        $is_valid = $this->sessionRepository->checkSession($auth['email'], $auth['token'], static::days, static::hours, static::minutes, $expiration_date);

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


}