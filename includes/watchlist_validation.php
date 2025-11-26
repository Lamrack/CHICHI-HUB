<?php
// includes/watchlist_validation.php

/**
 * Validate watchlist actions (add/remove).
 * Returns a concatenated error string. "" = valid.
 */
function validateWatchlistAction( $user_id, $movie_id)
{
    $error = "";

    if (!ctype_digit($user_id) || (int)$user_id <= 0) {
        $error .= "Invalid user ID. ";
    }

    if (!ctype_digit($movie_id) || (int)$movie_id <= 0) {
        $error .= "Invalid movie ID. ";
    }

    return $error;
}
