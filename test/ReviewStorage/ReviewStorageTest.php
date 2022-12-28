<?php

namespace App\ReviewStorage;

use App\Review;
use App\ReviewStorage;
use DateTime;
use PHPUnit\Framework\TestCase;

class ReviewStorageTest extends TestCase
{
    private static ?\PDO $pdo;
    private static ?\PDO $fakePdo;
    protected function setUp(): void
    {
        ReviewStorageTest::$pdo->query('truncate table reviews');
        ReviewStorageTest::$fakePdo->query('truncate table reviews');
    }

    public static function tearDownAfterClass(): void
    {
        ReviewStorageTest::$pdo = null;
        ReviewStorageTest::$fakePdo = null;
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

        ReviewStorageTest::$fakePdo = new \PDO('sqlite::memory:');
        ReviewStorageTest::$fakePdo->query('CREATE TABLE fakeReviews (
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
        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Все хорошо", "2022-05-12");'); // создание записи

        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Not found');
        $storage->getFeedbackByPage(-1);
    }

    public function testAddReview(): void
    {
        $review = new Review(1, 1, 5, 'Все хорошо', DateTime::createFromFormat('Y-m-d', '2022-05-12'));

        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        /*$storage->addReview($review);*/
        /*$review = $storage->getReviewById(1);

        $this->assertSame(1, $review->id);*/

        $this->assertNull($storage->addReview($review));
    }

    public function testAddReviewNotAdded(): void
    {
        $review = new Review(null, 1, 5, '', DateTime::createFromFormat('Y-m-d', '2022-05-12'));

        $storage = new ReviewStorage(ReviewStorageTest::$fakePdo);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Review was not added');
        $storage->addReview($review); // Добавление в несуществующую даблицу
    }

    public function testDeleteReview(): void
    {
        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Text", "2022-05-12");'); // создание записи

        $review = new Review(1, 1, 5, 'Text', new DateTime());
        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        $this->assertNull($storage->deleteReview($review));
    }

    public function testDeleteReviewNotFound(): void
    {
        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Text", "2022-05-12");'); // создание записи

        $review = new Review(null, 1, 5, 'Text', new DateTime());
        $storage = new ReviewStorage(ReviewStorageTest::$pdo);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Not found');
        $storage->deleteReview($review);
    }

    public function testDeleteReviewNotDeleted(): void
    {
        ReviewStorageTest::$pdo->query('INSERT INTO reviews (id, guest_id, rating, review, date)
                                                 VALUES (1, 1, 5, "Text", "2022-05-12");'); // создание записи

        $review = new Review(28, 1, 5, 'Text', new DateTime());

        $storage = new ReviewStorage(ReviewStorageTest::$fakePdo);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Review was not deleted');
        $storage->deleteReview($review);

    }

}