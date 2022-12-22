<?php

namespace App\Controllers;
use App\ReviewStorage;
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

        /*$result = json_encode($result, JSON_UNESCAPED_UNICODE);

        $result = json_decode($result, JSON_UNESCAPED_UNICODE);

        print_r($result[0]['rating']);*/

        return $response;
    }

    public function addingReviewByJs($request, $response) {
        // Получаю значения с формы в массив
        $data = $request->getParsedBody();

        $result = $this->reviewStorage->addReview($data);

        $response->getBody()->write(json_encode($result));

        return $response;
    }

    public function deleteReviewById($request, $response){
        // Получаю значения с формы в массив
        $data = $request->getParsedBody();

        $result = $this->reviewStorage->deleteReview($data);
        $response->getBody()->write($result);

        return $response;
    }
}