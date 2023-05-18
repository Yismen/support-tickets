{{-- @if ($user->isDepartmentAgent($this->department)) --}}
@if ($this->agent_id && $this->agent_id > 0)
<button class="btn btn-secondary btn-sm" wire:click.prevent='filterAgent()'>
    <i class="fa fa-filter-circle-dollar"></i>
    All Filters
</button>
@else
<button class="btn btn-primary btn-sm" wire:click.prevent='filterAgent({{ $user->id }})'>
    <i class="fa fa-filter"></i>
    Only Assigned to Me
</button>
@endif
{{-- @endif --}}