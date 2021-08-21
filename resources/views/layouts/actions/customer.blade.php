<div class="d-flex justify-content-left align-items-center">
    @if(isset($model->first_name) && !is_null($model->first_name))
        <div class="avatar-wrapper">
            <?php $avatarBg = ['bg-light-primary', 'bg-light-info', 'bg-light-danger', 'bg-light-warning', 'bg-light-secondary', 'bg-light-success']; ?>
            <div class="avatar {{ $avatarBg[array_rand($avatarBg)]}}  me-1">
            <span class="avatar-content">
                @if(!is_null($model->first_name) && !is_null($model->last_name))
                    {{ ucfirst(substr($model->first_name, 0, 1)) . ucfirst(substr($model->last_name, 0, 1)) }}
                @endif
            </span>
            </div>
        </div>
        <div class="d-flex flex-column">
            <a href="{{  route($route, "$model->id") }}" class="user_name text-truncate">
                <span class="fw-bold">{{ $model->first_name. ' '. $model->last_name }}</span>
            </a>
            {{--<h6 class="user-name text-truncate mb-0">{{ $model->first_name. ' '. $model->last_name }}</h6>--}}
            <small class="text-truncate text-muted text-break"
                   data-bs-toggle="tooltip"
                   data-bs-placement="top"
                   title="{{ $model->email }}">
                {{ substr($model->email, 0, 12) }}
            </small>
        </div>
    @else
    @endif
</div>
