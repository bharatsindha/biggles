<!-- start Modal-->
{{ Form::model($move, ['route' => ['move.accept_job', $move->id], 'method' => 'patch']) }}
@csrf
<div class="modal fade" id="acceptJobModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="acceptJobModal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px;">
        <div class="modal-content">
            <img class="close_icon" src="{{ asset('assets/media/close.svg') }}">
            <div class="modal-header flex-wrap">
                @if($updateStatus == 1)
                    <h5 class="modal-title" id="exampleModalLabel">Update job</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">Accept job and set dates</h5>
                    <p>Please specify an estimated date / time window for pickup and delivery. This helps us to know when to
                        schedule the final payment from the customer so you get paid on time.</p>
                    <p><b>Requested date: {{ !is_null($move->requested_date) && !empty($move->requested_date) ? Carbon\Carbon::parse($move->requested_date)->format('d M Y - H:i') : '' }}</b></p>
                @endif

            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-lg-6">
                        {!!  Form::text('pickup_window_start', old('pickup_window_start'),['id' => 'pickup_window_start','class' => 'form-control commonDatepicker', 'autocomplete'=>'off','required' => 'required']) !!}
                        @if($errors->has('pickup_window_start'))
                            <div class="text text-danger">
                                {{ $errors->first('pickup_window_start') }}
                            </div>
                        @endif
                        <span>Pickup Start Date</span>
                    </div>
                    <div class="col-lg-6">
                        {!!  Form::text('pickup_window_end', old('pickup_window_end'),['id' => 'pickup_window_end','class' => 'form-control commonDatepicker', 'autocomplete'=>'off','required' => 'required']) !!}
                        @if($errors->has('pickup_window_end'))
                            <div class="text text-danger">
                                {{ $errors->first('pickup_window_end') }}
                            </div>
                        @endif
                        <span>Pickup End Date</span>
                    </div>
                </div>
                @if($move->type != 4)
                    <div class="form-group row">
                        <div class="col-lg-6">
                            {!!  Form::text('delivery_window_start', old('delivery_window_start'),['id' => 'delivery_window_start','class' => 'form-control commonDatepicker', 'autocomplete'=>'off','required' => 'required']) !!}
                            @if($errors->has('delivery_window_start'))
                                <div class="text text-danger">
                                    {{ $errors->first('delivery_window_start') }}
                                </div>
                            @endif
                            <span>Delivery Start Date</span>
                        </div>
                        <div class="col-lg-6">
                            {!!  Form::text('delivery_window_end', old('delivery_window_end'),['id' => 'delivery_window_end','class' => 'form-control commonDatepicker', 'autocomplete'=>'off','required' => 'required']) !!}
                            @if($errors->has('delivery_window_end'))
                                <div class="text text-danger">
                                    {{ $errors->first('delivery_window_end') }}
                                </div>
                            @endif
                            <span>Delivery End Date</span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary font-weight-bold" value="{{ ($updateStatus == 1) ? 'Update job' : 'Accept job and set dates' }}">
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
<!-- end Modal-->
