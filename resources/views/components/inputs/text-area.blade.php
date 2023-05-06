@props([
'field',
'required' => true,
'editor' => false,
'modifier' => null
])
<div class="mb-3">
    <x-support::inputs.label :field="$field" :required="$required" :label="$slot" />
    <div @if ($editor) wire:ignore @endif>
        <textarea wire:model{{ $modifier }}='{{ $field }}' {{ $attributes->class([
       'is-invalid' => $errors->has($field),
       'form-control'
       ])->merge([
           'rows' => 5,
           'id' => $field 
           ]) }}
        ></textarea>

    </div>
    <x-support::inputs.error :field="$field" />


    @if ($editor)

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create( document.getElementById( "{{ $field }}" ), {
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
    @endif
</div>