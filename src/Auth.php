<?php

class Auth
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Try to log a user in.
     * 
     * @return array [success => bool, user_id => ?int, message => string]
     */
    public function attemptLogin(string $email, string $password): array
    {
        $email    = trim($email);
        $password = trim($password);

        if ($email === '' || $password === '') {
            return [
                'success' => false,
                'user_id' => null,
                'message' => 'Please fill in both fields.',
            ];
        }

        // Same logic as in public/login.php
        $stmt = $this->pdo->prepare("
            SELECT user_id, email, password_hash 
            FROM users 
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return [
                'success' => true,
                'user_id' => (int)$user['user_id'],
                'message' => 'OK',
            ];
        }

        return [
            'success' => false,
            'user_id' => null,
            'message' => 'Invalid email or password.',
        ];
    }
}
