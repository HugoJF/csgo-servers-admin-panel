@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="sub-header">Name History for: {{ $player->name }}</h1>

        @include('partials.player_name_history_list', [
            'player' => $player
        ])

    </div>
@endsection