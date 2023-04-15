<div>

    <x-support::modal title="{{ __('support::messages.department') }} - {{ $department->name ?? '' }}"
        modal-name="DepartmentDetails" event-name="{{ $this->modal_event_name_detail }}">

        <table class="table table-striped table-inverse table-sm">
            <tbody class="thead-inverse">
                <tr>
                    <th class="text-right">{{ __('support::messages.name') }}:</th>
                    <td class="text-left">{{ $department->name ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ __('support::messages.description') }}:</th>
                    <td class="text-left">{{ $department->description ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <h5 class="p-2 border-top">{{ __('support::messages.departments') }}</h5>
        @if ($department && $department->departments)
        <table class="table table-striped table-sm px-2">
            <thead class="thead-inverse">
                <tr>
                    <th>{{ __('support::messages.name') }}</th>
                    <th>{{ __('support::messages.email') }}</th>
                    <th>{{ __('support::messages.title') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($department->departments as $department)
                <tr>
                    <td scope="row">{{ $department->name }}</td>
                    <td>{{ $department->email }}</td>
                    <td>{{ $department->title }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <x-slot name="footer">
            <button class="btn btn-warning btn-sm" wire:click='$emit("updateDepartment", {{ $department->id ?? '' }})'>
                {{ __('support::messages.edit') }}
            </button>
        </x-slot>
    </x-support::modal>
</div>