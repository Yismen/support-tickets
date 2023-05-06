@if ($ticket?->replies->count())
<div class="px-3 mb-4 border-top pt-2">
    <h5 class="text-bold text-dark">
        {{ str(__('support::messages.replies'))->headline() }}
        <span class="badge badge-primary badge-btn">{{ $ticket->replies->count() }}</span>
    </h5>
    @foreach ($replies as $reply)
    <div class="text-sm border-bottom">
        <div class="p-2
                        @can('update', $reply)
                            ml-5 bg-light
                        @else
                            mr-5 bg-cyan
                        @endcan
                    ">
            <div class="d-flex justify-content-between">
                <div class="text-bold text-uppercase">{{ $reply->user->name }}</div>
                <div class="text-xs">{{ $reply->updated_at?->diffForHumans() }}</div>
            </div>
            <div class="d-flex justify-content-between">
                {{ $reply->content }}
                @can('update', $reply)
                <div class="">
                    <a wire:click.prevent='editReply({{ $reply->id }})' class="btn btn-link text-warning text-bold">{{
                        str(__("support::messages.edit"))->headline() }}</a>
                </div>
                @endcan
            </div>
        </div>
    </div>
    @endforeach
</div>

{{ $replies->links() }}
@endif