<?php

namespace App\Trait;

use Carbon\Carbon;

trait DateTraitHelper
{


    protected function formatDateValue(?array $dateValue): ?Carbon
    {
        if (
            !$dateValue ||
            !isset($dateValue['year'], $dateValue['month'], $dateValue['day'])
        ) {
            return null;
        }

        return Carbon::create(
            $dateValue['year'],
            $dateValue['month'],
            $dateValue['day']
        );
    }
    protected function formatToMDY(?array $dateValue): ?string
    {
        return $this->formatDateValue($dateValue)?->format('m/d/Y');
    }
    protected function formatToYMD(?array $dateValue): ?string
    {
        return $this->formatDateValue($dateValue)?->format('Y-m-d');
    }
}
