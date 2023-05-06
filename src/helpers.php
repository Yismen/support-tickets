<?php

use Flasher\Prime\Notification\Envelope;
use Flasher\Prime\Notification\NotificationInterface;

if (function_exists('supportTableName') === false) {
    function supportTableName(string $name)
    {
        return config('support.db_prefix') . $name;
    }
}

if (function_exists('str') === false) {
    function str(string $string)
    {
        return \Illuminate\Support\Str::of($string);
    }
}

if (function_exists('supportFlash') === false) {
    function supportFlash(string|null $message = null, string $type = NotificationInterface::SUCCESS, array $options = []): Envelope
    {
        return flasher($message, $type, $options);
    }
}

if (function_exists('supportConfirm') === false) {
    function supportConfirm(string $confirmation_name, string|null $message = 'Are you sure')
    {
        return sweetalert()
            ->showDenyButton(
                $showDenyButton = true,
                $denyButtonText = 'No',
                $denyButtonColor = null,
                $denyButtonAriaLabel = null
            )
            ->timer(0)
            ->option('confirmation_name', str($confirmation_name)->kebab())
            ->addInfo($message);
    }
}
