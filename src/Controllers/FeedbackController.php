<?php

namespace App\Controllers;
use App\Review;
use App\ReviewStorage;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FeedbackController {

    private ReviewStorage $reviewStorage;

    /**
     * @param ReviewStorage $reviewStorage
     */
    public function __construct(ReviewStorage $reviewStorage)
    {
        $this->reviewStorage = $reviewStorage;
    }

    public function getFeedbackById (ServerRequestInterface $request, ResponseInterface $response, array $args) {

        $id = (int)$args['id'];

        try {
            $result = $this->reviewStorage->getReviewById($id);
        } catch(\Exception $e) {
             $response = $response->withStatus(404);
             $result = array(
                 'status' => 404,
                 'error' => $e->getMessage(),
             );
        }

        $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));

        return $response;
    }

    public function getFeedbacksPageByPage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Берем значение page из GET запроса, если его нет, то выводится 1 страница
        $params = $request->getQueryParams();
        $page = isset($params['page']) ? (int)$params['page'] : 1;

        // Вызывается метод из reviewStorage
        try {
            $result = $this->reviewStorage->getFeedbackByPage($page);
        } catch(\Exception $e) {
            $response = $response->withStatus(404);
            $result = array(
                'status' => 404,
                'error' => $e->getMessage(),
            );
        }

        $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));

        return $response;
    }

    public function addingReview(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface{
        // Получаю значения с формы в массив
        $data = $request->getParsedBody();

        $review = new Review(null, $data['guest_id'], $data['rating'], $data['review'], DateTime::createFromFormat('Y-m-d', $data['date']));

        try {
            $this->reviewStorage->addReview($review);
            $response = $response->withStatus(201);
            $response->getBody()->write(json_encode(array('id' => $review->id)));
        } catch(\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(array(
                'status' => 500,
                'error' => $e->getMessage(),
            )));
        }

        return $response;
    }

    public function deleteReviewById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface{
        // Получаю значения с формы в массив
        $data = $request->getParsedBody();
        $id = $data['id'];
        try {
            $review = $this->reviewStorage->getReviewById($id);
        } catch (\Exception $e) {
            $response = $response->withStatus(404);
            $response->getBody()->write(json_encode(array('status' => 404, 'error' => $e->getMessage())));
            return $response;
        }
        try {
            $this->reviewStorage->deleteReview($review);
            $response = $response->withStatus(204);
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(array('error' => $e->getMessage(), 'status' => 500)));
        }

        return $response;
    }
}