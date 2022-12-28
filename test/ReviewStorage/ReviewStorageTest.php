<?php

namespace App\ReviewStorage;

use App\Review;
use App\ReviewStorage;
use DateTime;
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

    public function testGetReviewByIdNotFound(): void
    {
        $this->expectException(\Exception::class);

        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Все хорошо", "2022-05-12");'); // создание записи

        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        $storage->getReviewById(2);

        $this->expectExceptionMessage('Not found');
    }

    public function testGetFeedbackByPage(): void
    {
        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Все хорошо", "2022-05-12");'); // создание записи

        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        $review = $storage->getFeedbackByPage(1);

        $id = (int)$review[0]['id'];

        $this->assertSame(1, $id);
    }

    public function testGetFeedbackByPageNotFound(): void
    {
        $this->expectException(\Exception::class);

        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Все хорошо", "2022-05-12");'); // создание записи

        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        $storage->getFeedbackByPage(-1);

        $this->expectExceptionMessage('Not found');
    }

}