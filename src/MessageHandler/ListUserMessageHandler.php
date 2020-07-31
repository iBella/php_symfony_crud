<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Entity\Telephone;

use App\Message\ListUserMessage;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\UserRepository;

final class ListUserMessageHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(ListUserMessage $message)
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $message->setData($this->userToArray($user));
        }

        return new Envelope($message);
    }

    private function userToArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'telephones' => array_map(fn(Telephone $telephone) => [
                'number' => $telephone->getNumber()
            ], $user->getTelephones()->toArray())
        ];
    }
}
