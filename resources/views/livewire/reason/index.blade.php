<div>
    <livewire:support::reason.detail />
    <livewire:support::reason.form />
    <div class="d-flex justify-content-between">
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <livewire:support::reason.table />
                </div>
            </div>
        </div>
    </div>
</div>