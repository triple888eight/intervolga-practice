<?php

namespace App\Controllers;

use App\Review;
use App\ReviewStorage;
use DateTime;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class FeedbackControllerTest extends TestCase
{
    public function testGetFeedbackById(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $review = new Review(1, 1, 5, 'Text', new DateTime());

        $reviewStorage->expects($this->once())->method('getReviewById')->with(2)->willReturn($review);
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode($review, JSON_UNESCAPED_UNICODE));

        $controller->getFeedbackById($request, $response, array('id' => 2));
    }

    public function testGetFeedbackById_notFound(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $review = array('status' => 404, 'error' => 'Not found');

        $reviewStorage->expects($this->once())->method('getReviewById')->with(2)->willThrowException(new \Exception('Not found'));
        $response->expects($this->once())->method('withStatus')->with(404)->willReturnSelf();
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode($review, JSON_UNESCAPED_UNICODE));

        $controller->getFeedbackById($request, $response, array('id' => 2));
    }

    public function testGetFeedbackPageByPage(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $review = array(array('id' => 1, 'guest_id' => 10, 'rating' => 4));

        $reviewStorage->expects($this->once())->method('getFeedbackByPage')->with(1)->willReturn($review);
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode($review, JSON_UNESCAPED_UNICODE));

        $controller->getFeedbacksPageByPage($request, $response);
    }

    public function testGetFeedbackPageByPage_notFound(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $review = array('status' => 404, 'error' => 'Not found');

        $reviewStorage->expects($this->once())->method('getFeedbackByPage')->with(1)->willThrowException(new \Exception('Not found'));
        $response->expects($this->once())->method('withStatus')->with(404)->willReturnSelf();
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode($review, JSON_UNESCAPED_UNICODE));

        $controller->getFeedbacksPageByPage($request, $response);
    }

    public function testAddingReview(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $data = array('guest_id' => 1, 'rating' => 5, 'review' => 'Норм', 'date' => '2022-12-02');
        $request->expects($this->once())->method('getParsedBody')->willReturn($data);

        $reviewStorage->expects($this->once())->method('addReview')->with($this->callback(function (Review $r): bool {
            $this->assertSame(1, $r->guestId);
            $this->assertSame(5, $r->rating);
            $this->assertSame('Норм', $r->review);
            $this->assertSame('2022-12-02', $r->date->format('Y-m-d'));
            return true;
        }))->willReturnCallback(function (Review $r): void {
            $r->id = 1;
        });

        $response->expects($this->once())->method('withStatus')->with(201)->willReturnSelf();
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode(array('id' => 1), JSON_UNESCAPED_UNICODE));

        $controller->addingReview($request, $response);
    }

    public function testAddingReviewNotAdded():void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $data = array('guest_id' => 2, 'rating' => 5, 'review' => '', 'date' => '2022-12-02');
        $request->expects($this->once())->method('getParsedBody')->willReturn($data);

        $result = array('status' => 500, 'error' => 'Review was not added');

        $reviewStorage->expects($this->once())->method('addReview')->with($this->callback(function (Review $r): bool {
            $this->assertSame(2, $r->guestId);
            $this->assertSame(5, $r->rating);
            $this->assertSame('', $r->review);
            $this->assertSame('2022-12-02', $r->date->format('Y-m-d'));
            return true;
        }))->willThrowException(new \Exception('Review was not added'));

        $response->expects($this->once())->method('withStatus')->with(500)->willReturnSelf();
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode($result, JSON_UNESCAPED_UNICODE));

        $controller->addingReview($request, $response);
    }

    public function testDeleteReviewById(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $request->expects($this->once())->method('getParsedBody')->willReturn(array('id' => 1));
        $review = new Review(1, 1, 5, 'Text', new DateTime());

        $reviewStorage->expects($this->once())->method('getReviewById')->with(1)->willReturn($review);
        $reviewStorage->expects($this->once())->method('deleteReview')->with($review);
        $response->expects($this->once())->method('withStatus')->with(204)->willReturnSelf();

        $controller->deleteReviewById($request, $response);
    }

    public function testDeleteReviewByIdNotFound(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $request->expects($this->once())->method('getParsedBody')->willReturn(array('id' => 85));

        $result = array('status' => 404, 'error' => 'Not found');

        $reviewStorage->expects($this->once())->method('getReviewById')->with(85)->willThrowException(new \Exception('Not found'));
        $response->expects($this->once())->method('withStatus')->with(404)->willReturnSelf();
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode($result, JSON_UNESCAPED_UNICODE));

        $controller->deleteReviewById($request, $response);
    }

    public function testDeleteReviewByIdNotDeleted(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $reviewStorage = $this->createMock(ReviewStorage::class);
        $controller = new FeedbackController($reviewStorage);

        $request->expects($this->once())->method('getParsedBody')->willReturn(array('id' => 1));
        $review = new Review(1, 1, 5, 'Text', new DateTime());

        $result = array('status' => 500, 'error' => 'Review was not deleted');

        $reviewStorage->expects($this->once())->method('getReviewById')->with(1)->willReturn($review);
        $reviewStorage->expects($this->once())->method('deleteReview')->with($review)->willThrowException(new \Exception('Review was not deleted'));
        $response->expects($this->once())->method('withStatus')->with(500)->willReturnSelf();
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('write')->with(json_encode($result, JSON_UNESCAPED_UNICODE));

        $controller->deleteReviewById($request, $response);
    }

}
