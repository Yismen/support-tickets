{{-- Infographics --}}
<div class="row">
    <div class="col-sm-6 col-md-4 col-lg-3">
        <x-support::infographic :count='$total_tickets' icon="fa fa-ticket">Total Tickets
        </x-support::infographic>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <x-support::infographic :count=' $tickets_open' icon="fa fa-face-grin-wink">Tickets Open
        </x-support::infographic>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <x-support::infographic count=' {{ number_format($completion_rate * 100, 0) }}%' icon="fa fa-percent">
            Completion
            Rate
        </x-support::infographic>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <x-support::infographic count='{{ number_format($compliance_rate * 100, 0) }}%' icon="fa fa-percent">
            Compliance
            Rate
        </x-support::infographic>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <x-support::infographic count='{{ number_format($satisfaction_rate * 100, 0) }}%' icon="fa fa-percent">
            Satisfaction Rate
        </x-support::infographic>
    </div>
</div>