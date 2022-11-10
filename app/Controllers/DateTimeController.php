<?php

namespace App\Controllers;

use App\Core\Attributes\Get;

class DateTimeController
{
    #[Get("/datetimes")]
    public function index()
    {
        $dateTime1 = new \DateTime("11/08/2022 9:15 AM");
        $dateTime2 = new \DateTime("03/25/2022 4:25 PM");
        $interval = new \DateInterval("P3M2D"); // 3 months, 2 days
        $diff = $dateTime1->diff($dateTime2);
        // $period = new DatePeriod($dateTime2, new DateInterval("P1D"), 3, DatePeriod::EXCLUDE_START_DATE);
        $period = new \DatePeriod($dateTime2, new \DateInterval("P1D"), $dateTime1->modify("+1 day"));

        // foreach ($period as $date) {
        //     dump($date->format("m/d/Y"));
        // }

        dd(
            $diff,
            $diff->days,
            $diff->format("%R%a"),
            $diff->format("%Y years, %m months, %d days, %H:%i:%s"),
            (clone $dateTime1)->add($interval)->format("m/d/Y g:iA"),
            (clone $dateTime1)->sub($interval)->format("m/d/Y g:iA"),
            $period
        );
    }
}
