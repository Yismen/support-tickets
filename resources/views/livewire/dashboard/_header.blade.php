{{-- Header --}}
@if (auth()->user()->isSupportSuperAdmin())
<h4 class="text-uppercase text-bold">
    Super Admin Dashboard
</h4>
<div class="row justify-content-end">
    <div class="form-group col-4" id="filter-fixed">
        <div class="filter">
            <label for="department-filter-id" class="d-flex justify-content-between">
                Filter by Department
                @if (! empty($selected))
                <button class="btn btn-xs btn-dark" title="Clear Filters"
                    wire:click.prevent='$set("selected", "")'>X</button>
                @endif
            </label>
            <select class="form-control" wire:model='selected' id="department-filter-id">
                <option value="">All</option>
                @foreach ($departments as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
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