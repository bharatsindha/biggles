<!-- start Modal-->
{{ Form::model($move, ['route' => ['move.decline', $move->id], 'method' => 'patch']) }}
@csrf
<div class="modal fade" id="declineJobModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="declineJobModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img class="close_icon" src="{{ asset('assets/media/close.svg') }}">
            <div class="modal-header flex-wrap">
                <h5 class="modal-title" id="exampleModalLabel">Decline job</h5>
                <p>Please tell us why you are not able to do this job</p>
            </div>
            <div class="modal-body">
                <div class="form-group row">
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
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel
                </button>
                <input type="submit" class="btn btn-primary font-weight-bold" value="Decline and submit">
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
<!-- end Modal-->
