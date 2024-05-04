<?php
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\countOf;

include('functions.php');

class unitTests extends TestCase {
    /** @test */
    public function createUser() {
        $pdo = new PDO("mysql:host=localhost;  dbname=test;", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

        $this->assertSame(createUser($pdo, 12), 200);
    }
    /** @test */
    public function createBookmark() {
        $pdo = new PDO("mysql:host=localhost;  dbname=test;", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->assertSame(createBookmark($pdo, 12, 1, "test"), 200);
        $this->assertSame(createBookmark($pdo, 12, 2, "test2"), 200);
    }
    /** @test */
    public function getBookmarks() {
        $pdo = new PDO("mysql:host=localhost;  dbname=test;", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->assertSame(getBookmarks($pdo, 12)[0]['osm_id'], 1);
    }
    /** @test */
    public function deleteBookmark() {
        $pdo = new PDO("mysql:host=localhost;  dbname=test;", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->assertSame(deleteBookmark($pdo, 12, 1, "test"), 200);
        $this->assertSame(getBookmarks($pdo, 12)[0]['osm_id'], 2);
    }

    public function deleteUser() {
        $pdo = new PDO("mysql:host=localhost;  dbname=test;", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->assertSame(countOf(getBookmarks($pdo, 12)), 0);
    }
}