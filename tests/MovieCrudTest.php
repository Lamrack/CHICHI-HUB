<?php
use PHPUnit\Framework\TestCase;

// make sure validateMovie() is loaded
require_once __DIR__ . '/../src/movie_validation.php';
// then load the repository class
require_once __DIR__ . '/../src/MovieRepository.php';

class MovieCrudTest extends TestCase
{
    private PDO $pdo;
    private MovieRepository $movies;

    protected function setUp(): void
    {
        // 1) Load your actual DB connection
        require __DIR__ . '/../config/db.php';  // defines $pdo

        $this->assertInstanceOf(PDO::class, $pdo, 'PDO not created, check config/db.php');

        $this->pdo    = $pdo;
        $this->movies = new MovieRepository($this->pdo);

        // 2) Make sure movies table exists (for tests)
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS movies (
                movie_id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                genre VARCHAR(100) NULL,
                description TEXT NULL,
                release_year INT NULL,
                poster_url VARCHAR(255) NULL,
                netflix_url VARCHAR(255) NULL,
                prime_url VARCHAR(255) NULL,
                disney_url VARCHAR(255) NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 3) Clean up old test data
        $this->pdo
            ->prepare("DELETE FROM movies WHERE title LIKE 'Test Movie %'")
            ->execute();
    }

    /* -----------------------------
       ADD MOVIE TESTS
    ----------------------------- */

    public function testAddMovieFailsWithInvalidTitle()
    {
        // Use an EMPTY title, which validateMovie() should reject
        $res = $this->movies->addMovie(
            '',                    // invalid (blank title)
            'Drama',
            '2024',
            'Some description',
            'poster.jpg'
        );

        $this->assertFalse($res['success']);
        $this->assertNull($res['movie_id']);
        // We don't assume a specific message, just that there is an error
        $this->assertNotSame('', $res['message']);
    }

    public function testAddMovieSucceedsWithValidData()
    {
        $title = 'Test Movie Add OK';

        $res = $this->movies->addMovie(
            $title,
            'Action',
            '2023',
            'Nice action movie.',
            'poster.jpg',
            'https://netflix.test/movie',
            'https://prime.test/movie',
            'https://disney.test/movie'
        );

        $this->assertTrue($res['success']);
        $this->assertNotNull($res['movie_id']);
        $this->assertGreaterThan(0, $res['movie_id']);
        $this->assertSame('Movie added successfully.', $res['message']);

        // verify row in DB
        $stmt = $this->pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
        $stmt->execute([$res['movie_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($row);
        $this->assertSame($title, $row['title']);
        $this->assertSame('Action', $row['genre']);
        $this->assertSame('Nice action movie.', $row['description']);
        $this->assertEquals(2023, (int)$row['release_year']);
        $this->assertSame('poster.jpg', $row['poster_url']);
        $this->assertSame('https://netflix.test/movie', $row['netflix_url']);
        $this->assertSame('https://prime.test/movie', $row['prime_url']);
        $this->assertSame('https://disney.test/movie', $row['disney_url']);
    }

    /* -----------------------------
       EDIT MOVIE TESTS
    ----------------------------- */

    public function testUpdateMovieChangesFields()
    {
        // 1) Insert a movie first
        $create = $this->movies->addMovie(
            'Test Movie Edit Original',
            'Comedy',
            '2022',
            'Original description',
            'orig.jpg',
            null,
            null,
            null
        );
        $this->assertTrue($create['success']);
        $movieId = $create['movie_id'];

        // 2) Update it
        $update = $this->movies->updateMovie(
            $movieId,
            'Test Movie Edit Updated',
            'Sci-Fi',
            '2025',
            'Updated description',
            'updated.jpg',
            'https://netflix.test/updated',
            'https://prime.test/updated',
            'https://disney.test/updated'
        );

        $this->assertTrue($update['success']);
        $this->assertSame('Movie updated successfully.', $update['message']);

        // 3) Check DB
        $stmt = $this->pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
        $stmt->execute([$movieId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($row);
        $this->assertSame('Test Movie Edit Updated', $row['title']);
        $this->assertSame('Sci-Fi', $row['genre']);
        $this->assertSame('Updated description', $row['description']);
        $this->assertEquals(2025, (int)$row['release_year']);
        $this->assertSame('updated.jpg', $row['poster_url']);
        $this->assertSame('https://netflix.test/updated', $row['netflix_url']);
        $this->assertSame('https://prime.test/updated', $row['prime_url']);
        $this->assertSame('https://disney.test/updated', $row['disney_url']);
    }

    public function testUpdateMovieFailsWithInvalidData()
    {
        // Insert a valid movie first
        $create = $this->movies->addMovie(
            'Test Movie Edit Invalid',
            'Drama',
            '2020',
            'Valid description',
            'poster.jpg'
        );
        $this->assertTrue($create['success']);
        $movieId = $create['movie_id'];

        // Try to update with an INVALID title (blank)
        $update = $this->movies->updateMovie(
            $movieId,
            '',              // invalid (blank title)
            'Drama',
            '2020',
            'Valid description',
            'poster.jpg'
        );

            $this->assertFalse($update['success']);
            $this->assertNotSame('', $update['message']);
        }
    }
    