<?php

namespace Dainsys\Support\Http\Livewire\Traits;

use Flasher\Prime\Notification\Envelope;

/**
 * Add the capacity to handle sweet alert confirmation before performing certain actions.
 */
trait HasSweetAlertConfirmation
{
    public function sweetalertConfirmed(array $payload)
    {
        $method = $payload['envelope']['notification']['options']['confirmation_method'];

        $this->$method();
    }

    /**
     * Show a confirmation modal.
     *
     * @param  string                               $confirmation_method
     * @param  string                               $message
     * @return \Flasher\Prime\Notification\Envelope
     */
    public function confirm(string $confirmation_method, string|null $message = 'Are you sure?'): Envelope
    {
        return sweetalert()
            ->showDenyButton(
                $showDenyButton = true,
                $denyButtonText = 'No',
                $denyButtonColor = null,
                $denyButtonAriaLabel = null
            )
            ->timer(0)
            ->option('confirmation_method', str($confirmation_method))
        ->addInfo($message);
    }

    protected function getListeners()
    {
        return  array_merge(
            $this->listeners,
            ['sweetalertConfirmed']
        );
    }
}
