<?php

namespace App;

abstract class BankReporter
{
    abstract public function getBankConnector(): BankConnectorInterface;

    public function getBalance(): int
    {
        $bank = $this->getBankConnector();
        $bank->logIn();
        $balance = $bank->getBalance();
        $bank->logOut();
        return $balance;
    }
}