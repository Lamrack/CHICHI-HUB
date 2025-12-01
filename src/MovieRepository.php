<?php

require_once __DIR__ . '/movie_validation.php';

class MovieRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Create a new movie.
     *
     * @return array [success => bool, movie_id => ?int, message => string]
     */
    public function addMovie(
        string $title,
        string $genre,
        string $year,
        string $description,
        string $posterUrl = '',
        ?string $netflixUrl = null,
        ?string $primeUrl = null,
        ?string $disneyUrl = null
    ): array {
        // 1. Validate using your existing validateMovie()
        $error = validateMovie($title, $genre, $year, $description, $posterUrl);

        if ($error !== "") {
            return [
                'success'  => false,
                'movie_id' => null,
                'message'  => $error,
            ];
        }

        try {
            // Base insert – matches your current movie_add.php
            $stmt = $this->pdo->prepare("
                INSERT INTO movies (title, genre, description, release_year, poster_url, netflix_url, prime_url, disney_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $ok = $stmt->execute([
                $title,
                $genre !== "" ? $genre : null,
                $description !== "" ? $description : null,
                $year !== "" ? $year : null,
                $posterUrl !== "" ? $posterUrl : null,
                $netflixUrl,
                $primeUrl,
                $disneyUrl,
            ]);

            if (!$ok) {
                return [
                    'success'  => false,
                    'movie_id' => null,
                    'message'  => "Database error: could not add movie.",
                ];
            }

            $id = (int)$this->pdo->lastInsertId();

            return [
                'success'  => true,
                'movie_id' => $id,
                'message'  => "Movie added successfully.",
            ];
        } catch (PDOException $e) {
            return [
                'success'  => false,
                'movie_id' => null,
                'message'  => "DB exception: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Update an existing movie.
     *
     * @return array [success => bool, message => string]
     */
    public function updateMovie(
        int $movieId,
        string $title,
        string $genre,
        string $year,
        string $description,
        string $posterUrl = '',
        ?string $netflixUrl = null,
        ?string $primeUrl = null,
        ?string $disneyUrl = null
    ): array {
        if ($movieId <= 0) {
            return ['success' => false, 'message' => "Invalid movie ID."];
        }

        $error = validateMovie($title, $genre, $year, $description, $posterUrl);

        if ($error !== "") {
            return ['success' => false, 'message' => $error];
        }

        $stmt = $this->pdo->prepare("
            UPDATE movies
            SET title = ?, genre = ?, description = ?, release_year = ?, poster_url = ?, 
                netflix_url = ?, prime_url = ?, disney_url = ?
            WHERE movie_id = ?
        ");

        $ok = $stmt->execute([
            $title,
            $genre !== "" ? $genre : null,
            $description !== "" ? $description : null,
            $year !== "" ? $year : null,
            $posterUrl !== "" ? $posterUrl : null,
            $netflixUrl,
            $primeUrl,
            $disneyUrl,
            $movieId,
        ]);

        if (!$ok) {
            return ['success' => false, 'message' => "Database error while updating movie."];
        }

        if ($stmt->rowCount() === 0) {
            return ['success' => false, 'message' => "Movie not found or no changes made."];
        }

        return ['success' => true, 'message' => "Movie updated successfully."];
    }

    /**
     * Delete a movie by id.
     *
     * @return array [success => bool, message => string]
     */
    public function deleteMovie(int $movieId): array
    {
        if ($movieId <= 0) {
            return ['success' => false, 'message' => "Invalid movie ID."];
        }

        $stmt = $this->pdo->prepare("DELETE FROM movies WHERE movie_id = ?");
        $stmt->execute([$movieId]);

        if ($stmt->rowCount() === 0) {
            return ['success' => false, 'message' => "Movie not found."];
        }

        return ['success' => true, 'message' => "Movie deleted successfully."];
    }
}
