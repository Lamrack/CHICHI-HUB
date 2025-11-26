<?php

class UserRegister
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array [success => bool, message => string]
     */
    public function register(string $email, string $password, string $confirm): array
    {
        // 1) password match
        if ($password !== $confirm) {
            return ['success' => false, 'message' => "Passwords don't match."];
        }

        // 2) email valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => "Please enter a valid email address."];
        }

        // 3) email exists?
        $check = $this->pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            return ['success' => false, 'message' => "That email is already registered."];
        }

        // 4) insert user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $this->pdo->prepare("
            INSERT INTO users (email, password_hash)
            VALUES (?, ?)
        ");
        $ok = $ins->execute([$email, $hash]);

        if ($ok) {
            return ['success' => true, 'message' => "Account created successfully! You can now log in."];
        }

        return ['success' => false, 'message' => "Something went wrong. Please try again."];
    }
}
