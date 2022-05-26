<?php

namespace App\Traits;

trait FormatDateTrait{

    public function formatDateForOrder(string $date)
    {
        $split_date = explode('-',$date);
        $split_date = array_reverse($split_date);
        $split_date[count($split_date) - 1] = substr($split_date[count($split_date) - 1],-2);
        return implode('',$split_date);
    }
}
