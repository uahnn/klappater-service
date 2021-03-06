<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Uahnn\Transformers\Transformer;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response as IlluminateResponse;

class ApiController extends Controller {

    protected $statusCode = IlluminateResponse::HTTP_OK;

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data, array $headers = []) {
        return response($data, $this->getStatusCode(), $headers);
    }

    protected function respondWithPagination(Paginator $item, $data) {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $item->total(),
                'total_pages' => ceil($item->total() / $item->perPage()),
                'current_page' => $item->currentPage(),
                'prev_page' => $item->previousPageUrl(),
                'next_page' => $item->nextPageUrl(),
                'limit' => $item->perPage()
            ]
        ]);

        return $this->respond($data);
    }


    public function respondWithError($message) {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    public function respondNotFound($message = 'Not found') {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    public function respondBadInput($message = 'Bad input') {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->respondWithError($message);
    }

    public function respondInvalid($message = 'Invalid request') {
        return $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN)
            ->respondWithError($message);
    }

    public function respondNotAuthenticated($message = 'User not authenticated') {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)
            ->respondWithError($message);
    }

    public function respondNotAllowed($message = 'This action is not allowed') {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_ACCEPTABLE)
            ->respondWithError($message);
    }

    public function respondWithToken($data, $token) {
        return $this->respond([
            'data' => $data,
            'token' => $token
        ]);
    }

    public function respondCreatedWithToken($data, $token, $message = 'Resource successfully created.') {
        return $this->setStatusCode(IlluminateResponse::HTTP_CREATED)
            ->respond([
                'data' => $data,
                'token' => $token,
                'message' => $message
            ]);
    }

    public function respondCreated($data, $message = 'Resource successfully created.') {
        return $this->setStatusCode(IlluminateResponse::HTTP_CREATED)
            ->respond([
                'data' => $data,
                'message' => $message
            ]);
    }

    public function respondUpdated($data, $message = 'Resource succesfully updated.') {
        return $this->setStatusCode(IlluminateResponse::HTTP_OK)
            ->respond([
                'data' => $data,
                'message' => $message
            ]);
    }

    public function respondDeleted() {
        return $this->setStatusCode(IlluminateResponse::HTTP_NO_CONTENT)
            ->respond(null);
    }
}
