<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiResponseFactory
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILURE = 'fail';
    public const STATUS_ERROR = 'error';

    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param mixed $data
     * @param string|null $message
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function createSuccess(
        $data,
        ?string $message = null,
        int $status = SymfonyResponse::HTTP_OK,
        array $headers = []
    ): Response {
        return $this->responseFactory->make(
            [
                'status' => static::STATUS_SUCCESS,
                'message' => $message,
                'data' => $data,
            ],
            $status,
            $headers
        );
    }

    /**
     * @param string $message
     * @param array $errors
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function createError(
        string $message,
        array $errors,
        int $status = SymfonyResponse::HTTP_BAD_REQUEST,
        array $headers = []
    ): Response {
        return $this->responseFactory->make(
            [
                'status' => static::STATUS_ERROR,
                'message' => $message,
                'errors' => $errors,
            ],
            $status,
            $headers
        );
    }

    /**
     * @param string $message
     * @param array $errors
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function createFailure(
        string $message,
        array $errors = [],
        int $status = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = []
    ): Response {
        return $this->responseFactory->make(
            [
                'status' => static::STATUS_FAILURE,
                'message' => $message,
                'errors' => $errors,
            ],
            $status,
            $headers
        );
    }

    /**
     * @param array $items
     * @param int $totalItems
     * @param int $totalPages
     * @return Response
     */
    public function createList(array $items, int $totalItems, int $totalPages): Response
    {
        return $this->createSuccess([
            'meta' => [
                'items' => $totalItems,
                'pages' => $totalPages,
            ],
            'items' => $items,
        ]);
    }

    /**
     * @param LengthAwarePaginator $items
     * @return Response
     */
    public function createListFromPaginator(LengthAwarePaginator $items): Response
    {
        return $this->createList($items->items(), $items->total(), $items->lastPage());
    }
}
