<?php
// tests/WatchlistValidationTest.php

use PHPUnit\Framework\TestCase;

// ⬇️ Adjust path if needed
require_once __DIR__ . '/../includes/watchlist_validation.php';

class WatchlistValidationTest extends TestCase
{
    private string $goodUser;
    private string $goodMovie;

    protected function setUp(): void
    {
        $this->goodUser  = "1";
        $this->goodMovie = "27";
    }

    public function testValidIds()
    {
        $error = validateWatchlistAction($this->goodUser, $this->goodMovie);
        $this->assertSame("", $error);
    }

    public function testInvalidUserNonNumeric()
    {
        $error = validateWatchlistAction("abc", $this->goodMovie);
        $this->assertNotSame("", $error);
    }

    public function testInvalidMovieNonNumeric()
    {
        $error = validateWatchlistAction($this->goodUser, "xyz");
        $this->assertNotSame("", $error);
    }

    public function testInvalidUserZero()
    {
        $error = validateWatchlistAction("0", $this->goodMovie);
        $this->assertNotSame("", $error);
    }

    public function testInvalidMovieZero()
    {
        $error = validateWatchlistAction($this->goodUser, "0");
        $this->assertNotSame("", $error);
    }

    public function testInvalidUserNegative()
    {
        $error = validateWatchlistAction("-5", $this->goodMovie);
        $this->assertNotSame("", $error);
    }

    public function testInvalidMovieNegative()
    {
        $error = validateWatchlistAction($this->goodUser, "-10");
        $this->assertNotSame("", $error);
    }
}
