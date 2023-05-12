<div>
    <livewire:support::ticket.detail />
    <livewire:support::ticket.form />

    @include('support::livewire.dashboard._header')
    <div wire:poll.{{ config('support.polling_miliseconds') }}ms>
        @include('support::livewire.dashboard._infographics')

        @include('support::livewire.dashboard._charts')
    </div>
    {{-- Table --}}
    @include('support::livewire.dashboard._table')
    @push("styles")
    <style>
        .filter-fixed {
            background: aqua;
            padding: 10px;
            border-radius: 5px;
            position: fixed;
            top: 50px;
            z-index: 100;
            transition: all .5s;
        }
    </style>
    @endPush

    @push('scripts')
    <script>
        (function() {
            const element = document.getElementById('filter-fixed') ;
            // const position = element.getBoundingClientRect().bottom;
            const height = element.offsetHeight ;        

            document.addEventListener('scroll',  function(e) {
                updateClass();

                const currentPosition = scrollY;
            })

            let updateClass = function(){
                const scrolledPoss = window.scrollY;

                if (scrolledPoss > height * .9) {
                    element.classList.add('filter-fixed');
                } else {
                    element.classList.remove('filter-fixed');
                    
                }

            }
            Livewire.hook('message.processed', (message, component) => {
                updateClass();
            })

        })()  
    </script>
    @endPush
</div>