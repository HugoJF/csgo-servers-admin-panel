@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="sub-header">Status</h1>

        @include('partials.status_list', [
            'status' => $status
        ])

        {{ $status->links() }}

    </div>
@endsection