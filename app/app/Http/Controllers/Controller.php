<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getMessageFromErrors(array $errors): string
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            foreach ($error as $errorMessage) {
                $errorMessages[] = $errorMessage;
            }
        }

        return implode(", ", $errorMessages);
    }
}
