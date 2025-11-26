<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../src/UserRegister.php';

class UserRegisterTest extends TestCase
{
    private PDO $pdo;
    private UserRegister $register;

    protected function setUp(): void
    {
        // load your actual DB connection
        require __DIR__ . '/../config/db.php';  // C:\xampp\htdocs\CHICHI HUB\config\db.php

        $this->assertInstanceOf(PDO::class, $pdo, 'PDO not created, check config/db.php');

        $this->pdo = $pdo;
        $this->register = new UserRegister($this->pdo);

        // make sure users table exists and matches the register script
        // your register script only inserts (email, password_hash)
        // so we must NOT require username here
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(150) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // clean test email if it exists
        $this->pdo->prepare("DELETE FROM users WHERE email LIKE 'regtest_%@example.com'")->execute();
    }

    public function testFailsWhenPasswordsDontMatch()
    {
        $res = $this->register->register(
            'regtest_1@example.com',
            'secret123',
            'secret456'
        );

        $this->assertFalse($res['success']);
        $this->assertEquals("Passwords don't match.", $res['message']);
    }

    public function testFailsWhenEmailInvalid()
    {
        $res = $this->register->register(
            'not-an-email',
            'secret123',
            'secret123'
        );

        $this->assertFalse($res['success']);
        $this->assertEquals("Please enter a valid email address.", $res['message']);
    }

    public function testFailsWhenEmailAlreadyExists()
    {
        // insert a user first
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
        $stmt->execute(['regtest_2@example.com', password_hash('oldpass', PASSWORD_DEFAULT)]);

        // now try to register with same email
        $res = $this->register->register(
            'regtest_2@example.com',
            'secret123',
            'secret123'
        );

        $this->assertFalse($res['success']);
        $this->assertEquals("That email is already registered.", $res['message']);
    }

    public function testSucceedsWithValidData()
    {
        $email = 'regtest_3@example.com';

        $res = $this->register->register(
            $email,
            'secret123',
            'secret123'
        );

        $this->assertTrue($res['success']);
        $this->assertEquals("Account created successfully! You can now log in.", $res['message']);

        // also check DB row exists
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($user);
        $this->assertArrayHasKey('password_hash', $user);
    }
}
