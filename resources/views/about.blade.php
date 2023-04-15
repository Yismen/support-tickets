@extends(config('support.layout'))

@section('content')
<div class="card">
    <div class="card-body">
        {!! $content !!}
    </div>
</div>
@endsection