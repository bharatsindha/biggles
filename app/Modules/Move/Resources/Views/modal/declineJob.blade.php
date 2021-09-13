<!-- start Modal-->
{{ Form::model($move, ['route' => ['move.decline', $move->id], 'method' => 'patch']) }}
@csrf
<div class="modal fade" id="declineJobModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="declineJobModal" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header flex-wrap pb-0">
                <h5 class="modal-title" id="myModalLabel1">Decline job</h5>   
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <br>
            </div>
            <div class="modal-body pt-0">
                <div>
                    <p>Please tell us why you are not able to do this job</p>
                </div>
                <div class="form-group row mt-3">
                    <div class="col-lg-12">
                        {!! Form::textarea('decline_comment', old('decline_comment'), ['class'=>'form-control','id' => 'decline_comment', 'rows' => '3', 'cols' => '5']) !!}
                        @if($errors->has('decline_comment'))
                            <div class="text text-danger">
                                {{ $errors->first('decline_comment') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Cancel
                </button>
                <input type="submit" class="btn btn-primary font-weight-bold" value="Decline and submit">
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
<!-- end Modal-->
