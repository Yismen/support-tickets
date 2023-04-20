<div>
    <livewire:support::department.detail />
    <livewire:support::department.form />
    <div class="d-flex justify-content-between">
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <livewire:support::department.table />
                </div>
            </div>
        </div>
    </div>
</div>