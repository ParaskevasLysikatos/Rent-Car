<div class="mb-2">
    <div class="mb-2">
        <label for="file" class="mr-3">{{ isset($title) ? $title : _('Επισυναπτόμενα έγγραφα') }}: </label>
        <input type="file" class="form-control" id="files" name="files[]" multiple>
    </div>
    @if(isset($model))
        <div class="form-inline mb-2">
            <div class="transfer_info_title text-right">
                <label class="float-right mr-3">{{ __('Αρχεία') }}:</label>
            </div>
            <div class="transfer_info_option text-center">
                @if ($model->documents)
                    @foreach($model->documents as $file)
                        @document(['file' => $file, 'document_link_id' => $model->id, 'document_link_type' => get_class($model) ])
                        @enddocument
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>
