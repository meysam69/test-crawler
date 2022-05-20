<?php

namespace App;

class PasargadConnector implements BankConnectorInterface
{
    public function __construct(private string $username, private string $password, private string $captcha)
    {
    }


    public function logIn(): void
    {
        // TODO: Implement logIn() method.
    }

    public function logOut(): void
    {
        // TODO: Implement logOut() method.
    }

    public function getBalance(): int
    {
        // TODO: Implement getBalance() method.
    }
}