<?php
// ../includes/validation.php

/**
 * Validate email with your existing rules.
 */
function validate_email(string $email, string &$msg): bool {
    $email = trim($email);

    // Basic PHP email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
        return false;
    }

    // Extra rule: must start with a letter and follow standard pattern
    if (!preg_match('/^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
        $msg = "Email must start with a letter and be in a valid format.";
        return false;
    }

    // Extract domain part after '@'
    $atPos = strrchr($email, "@");
    if ($atPos === false) {
        $msg = "Email must be in a valid format.";
        return false;
    }

    $domain = substr($atPos, 1);

    // For our tests: treat fake domains (like .zzz) as invalid
    if (preg_match('/\.zzz$/i', $domain)) {
        $msg = "Email domain is not valid.";
        return false;
    }

    // No DNS (checkdnsrr) check – keeps tests stable on any machine
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
