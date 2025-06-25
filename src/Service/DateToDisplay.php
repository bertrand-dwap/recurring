<?php

namespace App\Service;


class DateToDisplay
{
    public function get(\DateTime $nextTime, int $nbDaysBeforeToDisplay): \DateTime
    {
        $today = new \DateTime('today');
        $interval = new \DateInterval('P' . $nbDaysBeforeToDisplay . 'D');
        $dateToDisplay = (clone $nextTime)->sub($interval);
        if ($dateToDisplay <= $today && $today <= $nextTime) {
            return $today;
        } else {
            return $nextTime;
        }
    }
}
