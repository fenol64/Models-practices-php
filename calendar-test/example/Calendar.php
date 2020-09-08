<?php

class Calendar {

    public $months = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
    public $daysinmouth = [];
    private $events = Array();
    public $test;

    public function build(): Array
    {

        for ($day=1; $day <= count($this->months); $day++) { 
            for ($mouth=1; $mouth <= $this->daysinmouth[$day]; $mouth++) { 
                $evento = $this->getEvents("{$day}/{$mouth}/".date('Y'));
                $this->events[$this->months[$day]][] = $evento;
            }
        }

        return $this->events;
    }


    public function getyearmouths(): Calendar
    {
        for ($i=1; $i <= 12; $i++) { 
            $this->daysinmouth[$i] = cal_days_in_month(CAL_GREGORIAN, $i, date('Y'));
        }

        return $this;
    }


    public function getEvents($date)
    {
        return Array(
            'Evento',
            date("d/m/Y", strtotime($date))
        );
    }
}
