<a href="#" class="btn btn-warning btn-sm mx-1"
    wire:click.prevent='$emit("update{{ $this->module }}", "{{ $row->id }}")'>{{
    __('Edit') }}</a>