<div>
    <livewire:support::subject.detail />
    <livewire:support::subject.form />
    <div class="d-flex justify-content-between">
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <livewire:support::subject.table />
                </div>
            </div>
        </div>
    </div>
</div>