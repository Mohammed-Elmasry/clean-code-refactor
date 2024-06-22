<?php

class Transaction
{
    public $type;
    public $amount;
    public $date;

    public function __construct($t, $a, $d)
    {
        $this->type = $t;
        $this->amount = $a;
        $this->date = $d;
    }
}