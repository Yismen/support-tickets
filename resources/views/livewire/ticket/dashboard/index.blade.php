<div>
    <x-support::loading />
    <livewire:support::ticket.detail />
    <livewire:support::ticket.form />

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
            <h3 class="text-bold"> {{ $department->name }}</h3>
        </div>
    </div>

    @if (auth()->user()->isSupportSuperAdmin())
    <div class="">
        <h5>Department filters</h5>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-default" wire:model='department'>
                <input type="radio" value="" checked> All
            </label>

            @foreach (\Dainsys\Support\Models\Department::orderBy('name')->get() as $department)
            <label class="btn btn-secondary">
                <input type="radio" value="{{ $department->id }}" wire:model='selected' :wire:key='$department->id'> {{
                $department->name }}
            </label>
            @endforeach
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
    <div class="card">
        <div class="card-body">
            <livewire:support::ticket.department.table :department='$department'
                :wire:key='"department-table-" . $department->id ?? null' />
        </div>
    </div>
</div>