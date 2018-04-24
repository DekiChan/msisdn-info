<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Custom Exception extension that enables the exception to 
 * return itself as JsonResponse.
 */
abstract class ResponsableException extends \Exception
{
    /**
     * @var int $_statusCode
     */
    private $_statusCode;

    public function __construct(
        string $message = 'Internal server error.', 
        int $statusCode = 500
    ) {
        $this->message = $message;
        $this->_statusCode = $statusCode;
    }
    
    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'message' => $this->message,
        ];
    }

    /**
     * @return int
     */
    public function recommendedHttpStatusCode() : int
    {
        return $this->_statusCode;
    }

    /**
     * Return exception as JSON response.
     * 
     * @param int|null $statusCode
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function toJsonResponse(int $statusCode = null) : JsonResponse
    {
        $status = $statusCode ?? $this->_statusCode;
        $payload = $this->toArray();

        return new JsonResponse($payload, $status);
    }
}
