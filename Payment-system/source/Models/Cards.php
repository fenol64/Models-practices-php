<?php


namespace source\Models;
use CoffeeCode\DataLayer\DataLayer;

class Cards extends DataLayer
{

    public function __construct()
    {
        // string $entity, array $required, string $primary = 'id', bool $timestamps = true
        parent::__construct("credit_cards", []);
    }
}