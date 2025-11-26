<?php
// tests/MovieValidationTest.php

use PHPUnit\Framework\TestCase;

// ⬇️ Adjust this path if needed
require_once __DIR__ . '/../includes/movie_validation.php';

class MovieValidationTest extends TestCase
{
    private string $goodTitle;
    private string $goodGenre;
    private string $goodYear;
    private string $goodDescription;
    private string $goodPoster;

    protected function setUp(): void
    {
        // “Good” baseline data like in your DOCX (class-level test data)
        $this->goodTitle       = 'Inception';
        $this->goodGenre       = 'Science Fiction';
        $this->goodYear        = '2010';
        $this->goodDescription = 'A valid movie description under 2000 characters.';
        $this->goodPoster      = 'uploads/inception.jpg';
    }

    /* ========================
       TITLE boundary tests
       ======================== */

    public function testTitleMinLessOne()
    {
        $title = ""; // should fail
        $error = validateMovie($title, $this->goodGenre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    public function testTitleMin()
    {
        $title = "A"; // 1 char – should pass
        $error = validateMovie($title, $this->goodGenre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testTitleMinPlusOne()
    {
        $title = "AB"; // 2 chars
        $error = validateMovie($title, $this->goodGenre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testTitleMaxLessOne()
    {
        $title = str_pad("", 199, "A");
        $error = validateMovie($title, $this->goodGenre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testTitleMax()
    {
        $title = str_pad("", 200, "A");
        $error = validateMovie($title, $this->goodGenre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testTitleMaxPlusOne()
    {
        $title = str_pad("", 201, "A");
        $error = validateMovie($title, $this->goodGenre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    public function testTitleExtremeMax()
    {
        $title = str_pad("", 500, "A");
        $error = validateMovie($title, $this->goodGenre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    /* ========================
       GENRE boundary tests
       ======================== */

    public function testGenreMin()
    {
        $genre = ""; // nullable – we allow empty here
        $error = validateMovie($this->goodTitle, $genre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testGenreMaxLessOne()
    {
        $genre = str_pad("", 99, "a");
        $error = validateMovie($this->goodTitle, $genre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testGenreMax()
    {
        $genre = str_pad("", 100, "a");
        $error = validateMovie($this->goodTitle, $genre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testGenreMaxPlusOne()
    {
        $genre = str_pad("", 101, "a");
        $error = validateMovie($this->goodTitle, $genre, $this->goodYear, $this->goodDescription, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    /* ========================
       YEAR boundary tests
       ======================== */

    public function testYearNonNumeric()
    {
        $year  = "abcd";
        $error = validateMovie($this->goodTitle, $this->goodGenre, $year, $this->goodDescription, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    public function testYearMinLessOne()
    {
        $year  = "1899";
        $error = validateMovie($this->goodTitle, $this->goodGenre, $year, $this->goodDescription, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    public function testYearMin()
    {
        $year  = "1900";
        $error = validateMovie($this->goodTitle, $this->goodGenre, $year, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testYearCurrentPlusOneAllowed()
    {
        $currentYear = (int)date("Y");
        $year        = (string)($currentYear + 1);
        $error       = validateMovie($this->goodTitle, $this->goodGenre, $year, $this->goodDescription, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testYearTooFarInFuture()
    {
        $currentYear = (int)date("Y");
        $year        = (string)($currentYear + 2);
        $error       = validateMovie($this->goodTitle, $this->goodGenre, $year, $this->goodDescription, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    /* ========================
       DESCRIPTION boundary tests
       ======================== */

    public function testDescriptionMin()
    {
        $desc  = "";
        $error = validateMovie($this->goodTitle, $this->goodGenre, $this->goodYear, $desc, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testDescriptionMaxLessOne()
    {
        $desc  = str_pad("", 1999, "d");
        $error = validateMovie($this->goodTitle, $this->goodGenre, $this->goodYear, $desc, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testDescriptionMax()
    {
        $desc  = str_pad("", 2000, "d");
        $error = validateMovie($this->goodTitle, $this->goodGenre, $this->goodYear, $desc, $this->goodPoster);

        $this->assertSame("", $error);
    }

    public function testDescriptionMaxPlusOne()
    {
        $desc  = str_pad("", 2001, "d");
        $error = validateMovie($this->goodTitle, $this->goodGenre, $this->goodYear, $desc, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    public function testDescriptionExtremeMax()
    {
        $desc  = str_pad("", 5000, "d");
        $error = validateMovie($this->goodTitle, $this->goodGenre, $this->goodYear, $desc, $this->goodPoster);

        $this->assertNotSame("", $error);
    }

    /* ========================
       POSTER URL boundary tests
       ======================== */

    public function testPosterUrlMax()
    {
        $poster = str_pad("a", 255, "b"); // length 255
        $error  = validateMovie($this->goodTitle, $this->goodGenre, $this->goodYear, $this->goodDescription, $poster);

        $this->assertSame("", $error);
    }

    public function testPosterUrlMaxPlusOne()
    {
        $poster = str_pad("a", 256, "b"); // length 256
        $error  = validateMovie($this->goodTitle, $this->goodGenre, $this->goodYear, $this->goodDescription, $poster);

        $this->assertNotSame("", $error);
    }
}
