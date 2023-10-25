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
        "currency_list_found_successfully" => "Currency list found successfully",
        "currency_successfully_created" => "Currency created successfully",
        "currency_successfully_found" => "Currency found successfully",
        "the_currency_was_successfully_diactivated" => "Currency deactivated successfully",
        "the_currency_was_successfully_activated" => "Currency activated successfully",
    ],
    "validations" => [],
    "errors" => [
        'the_onle_active_currencies_can_deactivate' => "Only active currencies can be deactivated.",
        'the_onle_inactive_currencies_can_activate' => "Only inactive currencies can be activated.",
    ],
    
];
