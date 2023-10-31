<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */
    "enums" => [],
    "messages" => [
        "currency_list_found_successfully" => "لیست ارزها با موفقیت یافت شد",
        "currency_successfully_created" => "ارز با موفقیت ایجاد شد",
        "currency_successfully_found" => "ارز با موفقیت یافت شد",
        "the_currency_was_successfully_diactivated" => "ارز با موفقیت غیرفعال شد",
        "the_currency_was_successfully_activated" => "ارز با موفقیت فعال شد",
    ],
    "validations" => [],
    "errors" => [
        'the_onle_active_currencies_can_deactivate' => "تنها ارزهای فعال می‌توانند غیرفعال شوند.",
        'the_onle_inactive_currencies_can_activate' => "تنها ارزهای غیرفعال می‌توانند فعال شوند.",
    ],

];
