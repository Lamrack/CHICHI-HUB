<?php

require_once __DIR__ . '/../src/validation.php';

use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    /* -----------------------------
       EMAIL VALIDATION TESTS
    ----------------------------- */

    public function testValidEmail()
    {
        $msg = "";
        $this->assertTrue(validate_email("john@example.com", $msg));
    }

    public function testInvalidEmailFormat()
    {
        $msg = "";
        $this->assertFalse(validate_email("invalid_email", $msg));
    }

    public function testEmailMustStartWithLetter()
    {
        $msg = "";
        $this->assertFalse(validate_email("1john@domain.com", $msg));
    }

    public function testInvalidDomain()
    {
        $msg = "";
        $this->assertFalse(validate_email("test@nonexistingdomain.zzz", $msg));
    }

    /* -----------------------------
       PASSWORD VALIDATION TESTS
    ----------------------------- */

    public function testStrongPassword()
    {
        $msg = "";
        $this->assertTrue(validate_password_strength("StrongPass1!", $msg));
    }

    public function testPasswordTooShort()
    {
        $msg = "";
        $this->assertFalse(validate_password_strength("A1!", $msg));
    }

    public function testPasswordMissingUppercase()
    {
        $msg = "";
        $this->assertFalse(validate_password_strength("weakpass1!", $msg));
    }

    public function testPasswordMissingLowercase()
    {
        $msg = "";
        $this->assertFalse(validate_password_strength("WEAKPASS1!", $msg));
    }

    public function testPasswordMissingNumber()
    {
        $msg = "";
        $this->assertFalse(validate_password_strength("NoNumber!", $msg));
    }

    public function testPasswordMissingSpecialChar()
    {
        $msg = "";
        $this->assertFalse(validate_password_strength("NoSpecial1", $msg));
    }
}
