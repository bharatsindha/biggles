<div class="row">
    <div class="col-12 d-flex flex-sm-row flex-column mt-2">
        <button type="submit" class="btn btn-primary mb-1 mb-sm-0 me-0 me-sm-1 waves-effect waves-float waves-light"
                id="form-btn-save">
            {{ isset($buttonTitle) && !empty($buttonTitle) ? $buttonTitle : 'Save' }}
        </button>
        @if(isset($buttonSaveAdd) && !empty($buttonSaveAdd))
            <button type="submit" name="__save_add_another"
                    class="btn btn-primary mb-1 mb-sm-0 me-0 me-sm-1 waves-effect waves-float waves-light"
                    id="form-btn-save">
                {{ isset($buttonSaveAdd) && !empty($buttonSaveAdd) ? $buttonSaveAdd : 'Save' }}
            </button>
        @endif
        <a href="{{ URL::previous() }}" class="btn btn-outline-secondary waves-effect" title="Cancel">Cancel</a>
    </div>
</div>
