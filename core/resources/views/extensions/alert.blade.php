@if (isset($errors) && count($errors) == 1)
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {!! $error !!}
        @endforeach
    </div>
@elseif(isset($errors) && count($errors) > 1)
    <div class="alert alert-danger">
        <p style="margin: 0;">
            @foreach ($errors->all() as $error)
                {!! $error !!}<br>
            @endforeach
        </p>
    </div>
@endif
@if(session('alert'))
    <div class="alert alert-{{ session('alert') }}">
        {!! session('message') !!}
    </div>
@endif
@if(session('status'))
    <div class="alert alert-warning">
        {!! session('status') !!}
    </div>
@endif