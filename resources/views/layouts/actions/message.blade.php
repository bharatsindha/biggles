@if( Session::has('success'))
    <div class="alert alert-success pos_fixed">
        <button data-dismiss="alert" class="close" type="button"><span>×</span><span class="sr-only">Close</span></button>
        <span class="text-semibold"> {{ Session::get('success') }}</span>
    </div>
@endif

@if( Session::has('error') )
    <div class="alert alert-danger pos_fixed">
        <button data-dismiss="alert" class="close" type="button"><span>×</span><span class="sr-only">Close</span></button>
        <span class="text-semibold"> {{ Session::get('error') }}</span>
    </div>
@endif
