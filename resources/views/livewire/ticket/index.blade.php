<div>
    <livewire:support::ticket.detail />
    <livewire:support::ticket.form />
    <div class="d-flex justify-content-between">
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <h3 class="border-bottom pb-3">My Tickets</h3>
                    <livewire:support::ticket.table />
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    @if (request("ticket_details"))
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.emit('showTicket', "{{ request('ticket_details') }}");
            
            setTimeout(() => {
                window.history.pushState({path: window.location.pathname}, '', window.location.pathname)
                
            }, 3000);
        });
    </script>
    @endif
    @endpush
</div>