@if( Session::has('success'))
    <div class="row alert_message">
        <div class="col">
            <div class="demo-spacing-0 mb-2">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        {{ Session::get('success') }}
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
@endif

@if( Session::has('error') )
    <div class="row">
        <div class="col">
            <div class="demo-spacing-0 mb-2">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        {{ Session::get('error') }}
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
@endif
