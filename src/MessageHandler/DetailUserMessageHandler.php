<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Entity\Telephone;

use App\Message\DetailUserMessage;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DetailUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(DetailUserMessage $message)
    {
        $user = $this->manager->getRepository(User::class)->find($message->getUserId());

        if (null === $user) {
            throw new \InvalidArgumentException('User with ID #' . $message->getUserId() . ' not found');
        }

        $message->setData($this->userToArray($user));

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
