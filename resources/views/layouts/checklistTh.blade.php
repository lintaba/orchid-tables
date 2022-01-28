<th @empty(!$width) width="{{$width}}" @endempty class="text-{{$align}}" data-column="{{ $slug }}">
    <div data-controller="bulkselect" class="cb-checker" data-bulkselect-id="{{$id}}">
        <label class="d-block" title="{{$title}}">
            <input type="checkbox" class="form-check-input cb-bulk">
            <span class="cb-counter cb-counter-{{$id}}" style="margin-left:5px;">n/a</span>
        </label>
        <x-orchid-popover :content="$popover"/>
    </div>
</th>
