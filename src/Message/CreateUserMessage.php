<?php

namespace App\Message;

final class CreateUserMessage
{
    private int $id;
    private string $name;
    private string $email;
    private array $telephones;

    public function __construct(string $name, string $email, array $telephones)
    {
        $this->name = $name;
        $this->email = $email;
        $this->telephones = $telephones;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getTelephones() : array
    {
        return $this->telephones;
    }

    public function setId(int $id) 
    {
        $this->id = $id;
    }

    public function getId() : int
    {
        return $this->id;
    }
}
