<?php
// includes/movie_validation.php

/**
 * Middle-layer validation for Movie data.
 * Returns a concatenated error string. "" means valid.
 */
function validateMovie(
    string $title,
    string $genre,
    string $year,
    string $description,
    string $posterUrl = ''
): string {
    $error = "";

    // ---- Title (NOT NULL, varchar(200)) ----
    if (strlen($title) === 0) {
        $error .= "Title may not be blank. ";
    }
    if (strlen($title) > 200) {
        $error .= "Title must be less than 200 characters. ";
    }

    // ---- Genre (varchar(100), nullable) ----
    if (strlen($genre) > 100) {
        $error .= "Genre must be less than 100 characters. ";
    }

    // ---- Year (YEAR(4)) ----
    if ($year === "") {
        // allow empty year only if your DB allows NULL; here we treat empty as error
        $error .= "Release year may not be blank. ";
    } elseif (!ctype_digit($year)) {
        $error .= "Release year must be numeric. ";
    } else {
        $intYear     = (int)$year;
        $currentYear = (int)date("Y");

        // boundary checks like DateAdded in your doc
        if ($intYear < 1900) {
            $error .= "Release year cannot be before 1900. ";
        }
        if ($intYear > $currentYear + 1) {
            $error .= "Release year cannot be far in the future. ";
        }
    }

    // ---- Description (TEXT, but we set a reasonable limit) ----
    if (strlen($description) > 2000) {
        $error .= "Description is too long (max 2000 characters). ";
    }

    // ---- Poster URL (varchar(255), optional) ----
    if (strlen($posterUrl) > 255) {
        $error .= "Poster URL must be less than 255 characters. ";
    }

    return $error;
}
