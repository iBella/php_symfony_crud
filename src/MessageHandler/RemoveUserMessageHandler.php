<?php

namespace App\MessageHandler;

use App\Message\RemoveUserMessage;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RemoveUserMessageHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(RemoveUserMessage $message)
    {
        $user = $this->userRepository->findById($message->getUserId());

        if (null === $user) {
            throw new \InvalidArgumentException('User with ID #' . $message->getUserId() . ' not found');
        }

        $this->userRepository->remove($user);
    }
}
