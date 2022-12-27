<?php

namespace App\ReviewStorage;

use App\ReviewStorage;
use PHPUnit\Framework\TestCase;

class ReviewStorageTest extends TestCase
{
    private static ?\PDO $pdo;
    protected function setUp(): void
    {
        ReviewStorageTest::$pdo->query('truncate table reviews');
    }

    public static function tearDownAfterClass(): void
    {
        ReviewStorageTest::$pdo = null;
    }

    public static function setUpBeforeClass(): void
    {
        ReviewStorageTest::$pdo = new \PDO('sqlite::memory:');
        ReviewStorageTest::$pdo->query('CREATE TABLE reviews (
                                                        id INTEGER PRIMARY KEY NOT NULL,
                                                        guest_id INTEGER,
                                                        rating VARCHAR,
                                                        review VARCHAR,
                                                        date VARCHAR
                                                    );'); // создание таблицы
    }

    public function testGetReviewById(): void
    {
        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Все хорошо", "2022-05-12");'); // создание записи
        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        $review = $storage->getReviewById(1);

        $this->assertSame(1, $review->id);
    }

}