<a href="#" class="btn btn-secondary btn-sm mx-1"
    wire:click.prevent='$emit("show{{ $this->module }}", "{{ $row->id }}")'>{{
    __('View') }}</a>