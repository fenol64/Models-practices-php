<?php 

    function url(String $uri = null): string
    {
        if (!empty($uri)) {
            return BASE_URL . $uri;
        } else {
            return BASE_URL;
        }
    }

    function brl(string $number = null): string
    {
        return number_format(floatval($number), 2, ",", ".");
    }

    function brlDate(string $date = null): Array
    {
        return ([
            "date" => date('d-m-Y', strtotime($date)),
            "day" => date('d', strtotime($date)),
            "month" => date('m', strtotime($date)),
            "year" => date('Y', strtotime($date))
        ]);
    }

