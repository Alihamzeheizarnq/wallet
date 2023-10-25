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
        "payment_list_found_successfully" => "Payment list found successfully",
        "payment_successfully_created" => "Payment created successfully",
        "payment_successfully_found" => "Payment found successfully",
        "the_payment_was_successfully_rejected" => "Payment rejected successfully",
        "the_payment_was_successfully_destroyed" => "Payment destroyed successfully",
        "the_payment_was_successfully_approved" => "Payment approved successfully",
    ],
    "validations" => [],
    "errors" => [
        "you_can_only_decline_pending_payments" => "You can only decline pending payments",
        "you_can_only_destroy_pending_payments" => "You can only destroy pending payments",
        "you_can_only_delete_pending_payments" => "You can only delete pending payments",
        "time_limit_on_creating_payment" => "You had a payment in the last :minutes minutes. Please wait",
    ],    
];
