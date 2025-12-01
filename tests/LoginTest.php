<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../src/Auth.php';

class LoginTest extends TestCase
{
    private PDO $pdo;
    private Auth $auth;

    protected function setUp(): void
    {
        // 1) Load your real PDO from config/db.php
        require __DIR__ . '/../config/db.php'; // must define $pdo

        $this->assertInstanceOf(PDO::class, $pdo, 'PDO not created, check config/db.php');

        $this->pdo  = $pdo;
        $this->auth = new Auth($this->pdo);

        // 2) Make sure users table exists (same structure used by register + login)
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(150) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 3) Clean test users
        $this->pdo
            ->prepare("DELETE FROM users WHERE email LIKE 'logintest_%@example.com'")
            ->execute();
    }

    public function testFailsWhenFieldsEmpty()
    {
        $res = $this->auth->attemptLogin('', '');

        $this->assertFalse($res['success']);
        $this->assertSame('Please fill in both fields.', $res['message']);
        $this->assertNull($res['user_id']);
    }

    public function testFailsWhenEmailDoesNotExist()
    {
        $res = $this->auth->attemptLogin('logintest_not_found@example.com', 'SomePassword1!');

        $this->assertFalse($res['success']);
        $this->assertSame('Invalid email or password.', $res['message']);
        $this->assertNull($res['user_id']);
    }

    public function testFailsWhenPasswordIsWrong()
    {
        // Arrange: create a user
        $email    = 'logintest_user@example.com';
        $hash     = password_hash('CorrectPass1!', PASSWORD_DEFAULT);
        $insert   = $this->pdo->prepare('INSERT INTO users (email, password_hash) VALUES (?, ?)');
        $insert->execute([$email, $hash]);

        // Act
        $res = $this->auth->attemptLogin($email, 'WrongPass1!');

        // Assert
        $this->assertFalse($res['success']);
        $this->assertSame('Invalid email or password.', $res['message']);
        $this->assertNull($res['user_id']);
    }

    public function testLoginSucceedsWithCorrectCredentials()
    {
        $email         = 'logintest_ok@example.com';
        $plainPassword = 'CorrectPass1!';

        // Arrange: insert user with known password hash
        $hash   = password_hash($plainPassword, PASSWORD_DEFAULT);
        $insert = $this->pdo->prepare('INSERT INTO users (email, password_hash) VALUES (?, ?)');
        $insert->execute([$email, $hash]);

        // Act
        $res = $this->auth->attemptLogin($email, $plainPassword);

        // Assert
        $this->assertTrue($res['success']);
        $this->assertSame('OK', $res['message']);
        $this->assertIsInt($res['user_id']);
        $this->assertGreaterThan(0, $res['user_id']);
    }
}
