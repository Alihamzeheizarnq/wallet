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
        "payment_list_found_successfully" => "فهرست پرداخت ها با موفقیت پیدا شد",
        "payment_successfully_created" => "پرداخت با موفقیت ایجاد شد",
        "payment_successfully_found" => "پرداخت با موفقیت پیدا شد",
        "the_payment_was_successfully_rejected" => "پرداخت با موفقیت رد شد",
        "the_payment_was_successfully_destroyed" => "پرداخت با موفقیت حذف شد",
        "the_payment_was_successfully_approved" => "پرداخت با موفقیت تایید شد",
    ],
    "validations" => [],
    "errors" => [
        "you_can_only_decline_pending_payments" => "شما فقط امکان رد پرداخت های در انتظار را دارید",
        "you_can_only_approve_pending_payments" => "شما فقط امکان تایید پرداخت های در انتظار را دارید",
        "you_can_only_destroy_pending_payments" => "شما فقط امکان حذف پرداخت های در انتظار را دارید",
        "you_can_only_delete_pending_payments" => "شما فقط امکان حذف پرداخت های در انتظار را دارید",
        "time_limit_on_creating_payment" => "شما یک پرداخت در :minutes دقیقه اخیر داشتید. لطفا صبر کنید",
    ],
];
