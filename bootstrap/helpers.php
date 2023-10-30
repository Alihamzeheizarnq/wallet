<?php

use App\Services\Response;

if ( ! function_exists('apiResponse')) {
    /**
     * apiResponse
     *
     * @return Response
     */
    function apiResponse(): Response
    {
        return app('api-response');
    }
}

if ( ! function_exists('generateUniqueNumber')) {
    /**
     * generateUniqueNumber
     *
     * @param int $length
     * @return int
     * @throws Exception
     */
    function generateUniqueNumber(int $length = 20): int
    {
        $uniqueNumber = str_shuffle(time().random_int(111111111, 999999999));

        $randomDigitNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomDigit = substr($uniqueNumber, rand(0, strlen($uniqueNumber) - 1), 1);
            $randomDigitNumber .= $randomDigit;
        }


        return $randomDigitNumber;
    }
}
