<?php


if(! function_exists('generate_unique_id')) {
    function generate_unique_id($prefix, $yearNow, $separator, $id, $createdAt) {
        $series = str_pad(1, 4, "0", STR_PAD_LEFT);

        $lastInsertedQuotationId = $id;

        if($lastInsertedQuotationId != 0) {
            $latestInsertedYear = $createdAt;

            if($yearNow = $latestInsertedYear) {
                $series = str_pad($lastInsertedQuotationId + 1, 4, "0", STR_PAD_LEFT);
            }
        }

        return $prefix . $yearNow . $separator . $series;
    }
}