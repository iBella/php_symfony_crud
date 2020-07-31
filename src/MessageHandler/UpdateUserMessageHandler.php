<?php

namespace App\MessageHandler;

use App\Message\UpdateUserMessage;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;

final class UpdateUserMessageHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function __invoke(UpdateUserMessage $message)
    {
        $user = $this->userRepository->findById($message->getId());

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
            throw new \InvalidArgumentException(print_r($violations));
        }

        $this->userRepository->persist($user);
    }
}
