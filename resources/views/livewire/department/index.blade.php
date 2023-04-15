<div>
    <livewire:support::department.detail />
    <livewire:support::department.form />
    <div class="d-flex justify-content-between">
        {{-- @if (count($department_files) > 0)
        <div class="pr-2" style="max-width: 50%!important; flex: 0;">
            <h5>{{ __('support::messages.departments_list') }}</h5>
            <ul class="list-group overflow-auto">
                @foreach ($department_files as $department)
                <li class="list-group-item d-flex justify-content-between ">
                    {{ $department }}
                    <button class="btn btn-primary bg-gradient btn-sm ml-2"
                        wire:click='$emit("createDepartment", "{{ str($department)->replace("\\", "\\\\") }}")'>Add</button>
                </li>
                @endforeach
            </ul>
        </div>
        @endif --}}
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <livewire:support::department.table />
                </div>
            </div>
        </div>
    </div>
</div>