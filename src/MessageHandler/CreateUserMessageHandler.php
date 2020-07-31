<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\CreateUserMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;

final class CreateUserMessageHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateUserMessage $message)
    {
        $user = new User($message->getName(), $message->getEmail());
        foreach ($message->getTelephones() as $telephone) {
            $user->addTelephone($telephone['number']);
        }

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $violations = array_map(fn(ConstraintViolationInterface $violation) => [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ], iterator_to_array($errors));
            throw new \InvalidArgumentException(print_r($violations));
        }

        $this->userRepository->persist($user);

        $message->setId($user->getId());

        return new Envelope($message);
    }
}
