<?php

namespace Dainsys\Support\Http\Livewire\Traits;

use Flasher\Prime\Notification\Envelope;

/**
 * Add the ccapacity to handle sweet alert confirmation before performing certain actions.
 */
/** @method array confirmationsContract() */
/** @method mixed confirm() */
trait HasSweetAlertConfirmation
{
    /**
     * Key pair array of the confirmations keys and the methods they respond to. For example,
     * if you call the `supportConfirm($confirmation_name)`, return an array containing
     * the $confirmation_name as key and the method name to respond as value:
     * `return ['reopen_ticket' => 'confirmReopen']`
     *
     * @return array
     */
    abstract protected function confirmationsContract(): array;

    public function sweetalertConfirmed(array $payload)
    {
        $method = $this->confirmationsContract()[$payload['envelope']['notification']['options']['confirmation_name']];

        $this->$method();
    }

    public function confirm(string $confirmation_name, string|null $message = 'Are you sure'): Envelope
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

    protected function getListeners()
    {
        return  array_merge(
            $this->listeners,
            ['sweetalertConfirmed']
        );
    }
}
