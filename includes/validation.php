<?php
// ../includes/validation.php

/**
 * Validate email with your existing rules.
 */
function validate_email(string $email, string &$msg): bool {
    $email = trim($email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
        return false;
    }

    if (!preg_match('/^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
        $msg = "Email must start with a letter and be in a valid format.";
        return false;
    }

    $atPos = strrchr($email, "@");
    if ($atPos === false) {
        $msg = "Email must be in a valid format.";
        return false;
    }

    $domain = substr($atPos, 1);
    if ($domain === '' || !checkdnsrr($domain, "MX")) {
        $msg = "Email domain is not valid. Please use a real email provider.";
        return false;
    }

    return true;
}

/**
 * Password strength check (your rules).
 */
function validate_password_strength(string $password, string &$msg): bool {
    if (strlen($password) < 8) {
        $msg = "Password must be at least 8 characters long.";
        return false;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $msg = "Password must contain at least one uppercase letter.";
        return false;
    }
    if (!preg_match('/[a-z]/', $password)) {
        $msg = "Password must contain at least one lowercase letter.";
        return false;
    }
    if (!preg_match('/[0-9]/', $password)) {
        $msg = "Password must contain at least one number.";
        return false;
    }
    if (!preg_match('/[\W_]/', $password)) {
        $msg = "Password must contain at least one special character.";
        return false;
    }
    return true;
}
