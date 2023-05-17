<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Reply;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Mail\ReplyCreatedMail;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Events\ReplyCreatedEvent;
use Dainsys\Support\Models\SupportSuperAdmin;
use Dainsys\Support\Enums\DepartmentRolesEnum;

class SendReplyCreatedMail
{
    protected Reply $reply;

    public function handle(ReplyCreatedEvent $event)
    {
        $this->reply = $event->reply;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new ReplyCreatedMail($this->reply));
        }
    }

    protected function recipients(): Collection
    {
        $super_admins = SupportSuperAdmin::get()->map->user;
        $department_admins = DepartmentRole::query()
            ->with('user')
            ->where('role', DepartmentRolesEnum::Admin)
            ->where('department_id', $this->reply->department_id)->get()->map->user;

        $recipients = (new Collection())
            ->merge($super_admins)
            ->merge($department_admins)
            ->push($this->reply->ticket->agent)
            ->push($this->reply->ticket->owner)
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
