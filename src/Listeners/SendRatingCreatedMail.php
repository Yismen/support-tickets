<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Rating;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Mail\RatingCreatedMail;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Models\SupportSuperAdmin;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Events\RatingCreatedEvent;

class SendRatingCreatedMail
{
    protected Rating $rating;

    public function handle(RatingCreatedEvent $event)
    {
        $this->rating = $event->rating;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new RatingCreatedMail($this->rating));
        }
    }

    protected function recipients(): Collection
    {
        $super_admins = SupportSuperAdmin::get()->map->user;
        $department_admins = DepartmentRole::query()
            ->with('user')
            ->where('role', DepartmentRolesEnum::Admin)
            ->where('department_id', $this->rating->department_id)->get()->map->user;

        $recipients = (new Collection())
            ->merge($super_admins)
            ->merge($department_admins)
            ->push($this->rating->ticket->agent)
            // ->push($this->rating->ticket->owner)
            ->filter(function ($user) {
                return $user?->email;
            });

        return config('support.email.include_current_user', false)
            ? $recipients
            : $recipients->filter(function ($user) {
                return $user->id !== auth()->user()?->id;
            });
    }
}