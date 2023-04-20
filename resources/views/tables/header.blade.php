<h4 class="align-items-center d-flex fs-5 justify-content-between">
    <div>
        {{ str($module)->headline()->plural() }} {{ __('support::messages.table') }}
        <span class="badge badge-btn bg-primary bg-gradient">{{ $count ?? 0 }}</span>
    </div>

    @include('support::tables.create', ['module' => $module ])
</h4>