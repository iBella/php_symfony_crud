<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\UpdateUserMessage;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function __invoke(UpdateUserMessage $message)
    {
        $user = $this->manager->getRepository(User::class)->find($message->getId());

        if (null === $user) {
            throw new \InvalidArgumentException('User with ID #' . $message->getId() . ' not found');
        }

        $user->setName($message->getName());
        $user->setEmail($message->getEmail());

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $violations = array_map(fn(ConstraintViolationInterface $violation) => [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ], iterator_to_array($errors));
            return $violations;
        }

        $this->manager->persist($user);
        $this->manager->flush();
    }
}
