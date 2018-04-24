<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Exceptions\ResponsableException;

/**
 * Custom implementation of response handling. 
 * 
 * Symfony requires Twig bundle in order to return the response as JSON 
 * (see https://github.com/symfony/symfony/issues/25905), but we don't
 * want to include it.
 */
class ExceptionListener
{
    /**
     * Default response body.
     * 
     * @var array $_defaultPayload
     */
    private $_defaultPayload = [
        'message' => 'Internal server error.',
    ];

    /**
     * @var int $_defaultStatusCode
     */
    private $_defaultStatusCode = 500;

    /**
     * Gets called in response to kernel.exception event, see also services.yaml
     * 
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // event propagation will be stopped and the response will be sent to the client when
        // setResponse() method on $event will be called
        // https://symfony.com/doc/current/controller/error_pages.html#working-with-the-kernel-exception-event
        $exception = $event->getException();

        if ($exception instanceof ResponsableException) {
            $event->setResponse($exception->toJsonResponse());
            return;
        }
        
        $payload = $this->_defaultPayload;
        $statusCode = $this->_defaultStatusCode;

        $response = new JsonResponse($payload, $statusCode);

        $event->setResponse($response);
    }
}
