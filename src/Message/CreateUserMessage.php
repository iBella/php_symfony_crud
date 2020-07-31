<?php

namespace App\Message;

final class CreateUserMessage
{
    private int $id;
    private string $name;
    private string $email;
    private array $telephones;

    public function __construct(string $name, string $email, array $telefones)
    {
        $this->name = $name;
        $this->email = $email;
        $this->telefones = $telefones;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmail() : string
    {
        return $this->email;
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
