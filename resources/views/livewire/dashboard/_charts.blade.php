{{-- / Charts --}}
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <livewire:support::charts.weekly-tickets-count :department='$department' height="200px"
                    key="weekly-tickets-count-{{ rand() }}" />
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <livewire:support::charts.weekly-tickets-count-by-subject :department='$department' height="200px"
                    key="weekly-tickets-count-by-subject-{{ rand() }}" />
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <livewire:support::charts.weekly-tickets-completion-rate :department='$department' height="200px"
                    key="weekly-tickets-completion-rate-{{ rand() }}" />
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <livewire:support::charts.weekly-tickets-compliance-rate :department='$department' height="200px"
                    key="weekly-tickets-compliance-rate-{{ rand() }}" />
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <livewire:support::charts.weekly-tickets-satisfaction-rate :department='$department' height="200px"
                    key="weekly-tickets-satisfaction-rate-{{ rand() }}" />
            </div>
        </div>
    </div>
</div>