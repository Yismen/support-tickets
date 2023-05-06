<div class="d-flex">
    @if ($column->getComponent()->show_button)
    @include('support::tables.show')
    @endif


    @if ($column->getComponent()->edit_button)
    @include('support::tables.edit')
    @endif
</div>