<div class="w-100 " x-data="{ open: false }">

    @can('rate-ticket', $ticket)
    <button class="btn btn-xs btn-info align-self-end" x-show="! open" @click.prevent="open = true">
        {{ str(__('support::messages.rate_service'))->headline() }}
    </button>

    <x-support::loading target="rateTicket">
        <form class="needs-validation bg-light disabled" autocomplete="false" wire:submit.prevent='rateTicket'
            x-show="open">

            <div class="d-flex flex-column">
                <div class="align-self-end mt-2 mr-2">
                    <button class="btn btn-xs btn-secondary " @click.prevent="open = false"
                        title="{{ str(__('support::messages.cancel'))->headline() }}">
                        X
                    </button>
                </div>

                <div class="d-flex flex-column">
                    <div class="input-group">
                        @foreach ($ratings as $rating)
                        <div class="form-check form-check-inline m-1 border rou px-1">
                            <input class="form-check-input" type="radio" wire:model='rating.score'
                                id="rating-{{ $loop->index }}" value="{{ $rating->value }}">
                            <label class="form-check-label p-1 text-xs" for="rating-{{ $loop->index }}" title="{{ str($rating->name)->headline()
                                }}">
                                @foreach (range(1, $rating->value) as $star)
                                <i class="fa fa-star"></i>
                                @endforeach
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <x-support::inputs.error :field="'rating.score'" />
                </div>

                <div class="align-items-center p-3">
                    <x-support::inputs.text-area field='rating.comment' rows="2" modifier=".defer" :required='false'>
                        {{ str(__('support::messages.comment'))->headline() }}:
                    </x-support::inputs.text-area>

                    <button class="btn btn-secondary btn-sm">
                        {{ str(__('support::messages.rate_service'))->headline() }}
                    </button>
                </div>
            </div>
        </form>
    </x-support::loading>

    @endcan
</div>