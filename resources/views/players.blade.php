@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="sub-header">Players</h1>

        @include('partials.player_list', [
            'players' => $players
        ])


    </div>
@endsection