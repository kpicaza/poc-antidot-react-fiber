<?php

declare(strict_types=1);

namespace App\Application\Http\Handler;

use App\Application\Event\SomeEvent;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Trowski\ReactFiber\FiberLoop;
use function React\Promise\resolve;

class HomePage implements RequestHandlerInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private FiberLoop $loop
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $eventDispatcher = $this->eventDispatcher;
        $this->loop->await(resolve($eventDispatcher->dispatch(SomeEvent::occur())));

        $message = $this->loop->await(resolve(sprintf(
            'Hello %s!!!! Welcome to Antidot Framework Starter',
            $this->loop->await(resolve($request->getAttribute('greet')))
        )));

        return new JsonResponse([
            'docs' => 'https://antidotfw.io',
            'message' => $message
        ]);
    }
}
