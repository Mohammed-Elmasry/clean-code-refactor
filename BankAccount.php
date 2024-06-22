<?php

class BankAccount
{
    private $name;
    private $accNumber;
    private $balance;
    private $transactions = array();
    private $authService;
    private $pin;

    public function __construct($name, $accNumber, AuthService $authService, $pin)
    {
        $this->name = $name;
        $this->accNumber = $accNumber;
        $this->balance = 0.0; // balance
        $this->authService = $authService;
        $this->pin = $pin; // PIN for ATM use
    }

    private function checkPin($enteredPin)
    {
        return $this->pin === $enteredPin;
    }

    public function deposit($amount, $enteredPin)
    { // Using primitive type for amount
        if ($this->authService->isAuthenticated()) {
            if ($this->checkPin($enteredPin)) {
                $this->balance += $amount;
                $this->transactions[] = new Transaction("deposit", $amount, date('Y-m-d H:i:s'));
            } else {
                echo "Incorrect PIN. Deposit failed.\n";
            }
        } else {
            echo "User not authenticated. Deposit failed.\n";
        }
    }

    public function withdraw($amount, $enteredPin)
    { // Using primitive type for amount
        if ($this->authService->isAuthenticated()) {
            if ($this->checkPin($enteredPin)) {
                if ($this->balance >= $amount) {
                    $this->balance -= $amount;
                    $this->transactions[] = new Transaction("withdraw", $amount, date('Y-m-d H:i:s'));
                } else {
                    echo "Insufficient funds\n";
                }
            } else {
                echo "Incorrect PIN. Withdrawal failed.\n";
            }
        } else {
            echo "User not authenticated. Withdrawal failed.\n";
        }
    }

    public function transfer(BankAccount $targetAccount, $amount, $enteredPin)
    {
        if ($this->authService->isAuthenticated()) {
            if ($this->checkPin($enteredPin)) {
                if ($this->balance >= $amount) {
                    $this->withdraw($amount, $enteredPin);
                    $targetAccount->deposit($amount, $enteredPin);
                    echo "Transferred " . $amount . " to account " . $targetAccount->getAccNumber() . "\n";
                } else {
                    echo "Insufficient funds to transfer\n";
                }
            } else {
                echo "Incorrect PIN. Transfer failed.\n";
            }
        } else {
            echo "User not authenticated. Transfer failed.\n";
        }
    }

    public function printStatement($enteredPin)
    {
        if ($this->authService->isAuthenticated()) {
            if ($this->checkPin($enteredPin)) {
                echo "Account Statement for: " . $this->name . "\n";
                foreach ($this->transactions as $t) {
                    echo $t->getType() . " of " . $t->getAmount() . " on " . $t->getDate() . "\n";
                }
                echo "Current balance: " . $this->balance . "\n";
            } else {
                echo "Incorrect PIN. Cannot print statement.\n";
            }
        } else {
            echo "User not authenticated. Cannot print statement.\n";
        }
    }

    public function getAccNumber()
    {
        return $this->accNumber;
    }
}