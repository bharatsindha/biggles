<div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            <h2 class="content-header-title float-start mb-0">
                {{ isset($moduleTitle) ? $moduleTitle : \Illuminate\Support\Facades\Config::get('app.name') }}
            </h2>
            <div class="breadcrumb-wrapper">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                    @if(isset($moduleTitle))
                        <li class="breadcrumb-item">
                            <a href="{{ isset($moduleLink) ? $moduleLink : '#' }}">
                                {{ isset($moduleTitle) ? $moduleTitle : '' }}
                            </a>
                        </li>
                    @endif
                    @if(isset($subTitle))
                        <li class="breadcrumb-item active">{{ isset($subTitle) ? $subTitle : '' }}</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>
