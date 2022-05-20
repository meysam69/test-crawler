<?php

namespace App;

interface BankConnectorInterface
{
    public function logIn(): void;

    public function logOut(): void;

    public function getBalance(): int;
}