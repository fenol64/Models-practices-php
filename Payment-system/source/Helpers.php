<?php

    function url(string $uri = null): string
    {
        if (!empty($uri)) {
            return BASE_URL . "{$uri}";
        } else {
            return BASE_URL;
        }
    }

    function formatBRL(float $number): string
    {
        return number_format($number, "2", ",", ".");
    }
