<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Ticket'), $ticket->name]) : join(" ", [__('Create'),
    __('New'), __('Ticket') ])
    @endphp

    <x-support::modal modal-name="TicketForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false" class="modal-xl">

        <x-support::form :editing="$editing">
            <div class="p-3">

                <div class="row">
                    <div class="col-sm-6">
                        <x-support::inputs.select field='ticket.department_id' :options='$departments'>
                            {{ str(__('support::messages.department'))->headline() }}:
                        </x-support::inputs.select>
                    </div>

                    <div class="col-sm-6">
                        <x-support::inputs.select field='ticket.reason_id' :options='$reasons'>
                            {{ str(__('support::messages.reason'))->headline() }}:
                        </x-support::inputs.select>
                    </div>
                </div>

                <div wire:ignore>
                    <x-support::inputs.text-area field="ticket.description" rows="10" id="editor">
                        {{ str(__('support::messages.description'))->headline() }}:
                    </x-support::inputs.text-area>
                </div>

                <x-support::inputs.error :field="'ticket.description'" />

                <x-support::inputs.radio-group field='ticket.priority' :options='$priorities' :placeholder=false
                    class="form-check-inline">
                    {{ str(__('support::messages.priority'))->headline() }}:
                </x-support::inputs.radio-group>


            </div>
        </x-support::form>
    </x-support::modal>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
            ]
        }
    } )
            .then((editor) => {
                editor.model.document.on('change:data', () => {
                    @this.set('ticket.description', editor.getData());
                })
                
                document.addEventListener('closeAllModals', () => {
                    let currentData = @this.get('ticket.description');

                    if (currentData) {
                        editor.setData(@this.get('ticket.description'));                        
                    } else {
                        editor.setData('')
                    }
                })
            })
            .catch( error => {
                console.error( error );
            } );
    </script>
    @endpush
</div>