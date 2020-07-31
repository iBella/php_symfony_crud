<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\CreateUserMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CreateUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function __invoke(CreateUserMessage $message)
    {
        $user = new User($message->getName(), $message->getEmail());
        /*foreach ($this->telephones as $telephone) {
            $user->addTelephone($telephone['number']);
        }*/

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

        $message->setId($user->getId());

        return new Envelope($message);
    }
}
