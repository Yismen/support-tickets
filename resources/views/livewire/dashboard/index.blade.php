<div>
    <x-support::loading />
    <livewire:support::ticket.detail />
    <livewire:support::ticket.form />



    @if (auth()->user()->isSupportSuperAdmin())
    <div class="row justify-content-end">
        <div class="form-group col-4" wire:ignore>
            <label for="">Filter by Department</label>
            <select class="form-control" wire:model='selected'>
                <option value="">All</option>
                @foreach ($departments as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <h1 class="border-bottom pb-2 text-uppercase text-black-50 mb-3"
                title="You Are {{ auth()->user()->departmentRole->role->name }}">
                {{
                join(' ',
                [

                str(__('support::messages.department'))->headline(),
                str(__('support::messages.dashboard'))->headline(),
                ])
                }}
                <i
                    class="fa {{ auth()->user()->isDepartmentAdmin($department) ? 'fa-cog text-primary' : 'fa-users text-secondary' }}"></i>
            </h1>
            <h3 class="text-bold"> {{ $department?->name }}</h3>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-sm-6 col-md-4 col-lg-3">
            <x-support::infographic :count='$total_tickets' icon="fa fa-ticket">Total Tickets</x-support::infographic>
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
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    total tickets by category, sorted by count, only top 10
                    <livewire:support::charts.weekly-tickets height="150px" />
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    Weekly total tickets
                    <livewire:support::charts.weekly-tickets height="150px" />
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    weekly completion rate
                    <livewire:support::charts.weekly-tickets height="150px" />
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    weekly compliance rate
                    <livewire:support::charts.weekly-tickets height="150px" />
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <livewire:support::dashboard.table :department='$department'
                wire:key="department-table-{{ $department?->id ?? null }}" />
        </div>
    </div>
</div>