@props([
'image',
'field',
'currentImage' => null
])

<div class="mb-2 bg-light p-2 shadow-sm border rounded ">

    <div class="d-flex">

        <div class="flex-fill">
            <label for="{{ $field }}" class="w-100">
                {{ $slot }}
                <input type="file" id="{{ $field }}" wire:model="{{ $field }}" class="file dropzone" draggable="true">
            </label>
            <x-support::inputs.error :field="$field" />
        </div>

        @if ($currentImage)
        <div class="ml-2">
            <h6 class="text-bold">Current image</h6>

            <div class="mb-2 d-block">
                <img src="{{ asset('storage/'.$currentImage) }}" style="max-width: 100px; max-height: 118px;">
            </div>
        </div>
        @endif

        {{-- Previewer --}}
        @if ($image)
        @php
        try {
        $url = $image->temporaryUrl();
        } catch (\Throwable $th) {
        $url = '';
        }
        @endphp
        <div class="d-flex flex-column ml-2">
            <div class="d-flex justify-content-between align-content-center align-items-start">
                <span class="text-wrap text-bold text-cyan ">
                    New Preview
                </span>
                <span class="">
                    <a href="#" class="btn btn-secondary btn-xs " title="Cancel"
                        wire:click.prevent='$set("{{ $field }}", "")'> X
                    </a>
                </span>
            </div>
            <img src="{{ $url }}" style="max-width: 100px; max-height: 118px;">
        </div>
        @endif
    </div>

    @push('styles')
    <style>
        .dragging:hover {
            cursor: grabbing !important;
            background-color: aquamarine !important;
        }

        .dragging {
            cursor: grabbing !important;
            background-color: aquamarine !important;
        }

        .file {
            cursor: pointer;
            border: 1px dashed;
            width: 100%;
            min-height: 120px;
            display: flex;
            position: relative;
        }

        .file::after {
            position: absolute;
            width: 100%;
            background-color: antiquewhite;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: right;
            top: 0;
            left: 0;
            height: 100%;
            content: "Click or drop files";
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        let dropzones = document.querySelectorAll('.dropzone');

        dropzones.forEach(function(dropzone) {
            dropzone.addEventListener('dragenter', (e) => {
                dropzone.classList.add('dragging');
            });

            dropzone.addEventListener('dragleave', (e) => {
                dropzone.classList.remove('dragging');
            });

            dropzone.addEventListener("drop", (e) => {
                console.log(e.target.files);
                console.log(e.target);
                console.log(@this)

                // drop++;
                // Drop.innerText = `Drop: ${drop}`;
                //move draggable div to target dropzone
                // dropzone.append(content);
            });

        })
    </script>
    @endpush
</div>