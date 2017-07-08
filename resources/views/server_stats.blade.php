@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Dashboard</h1>

        @foreach($servers as $server)
            <h2>{{ $server['name'] }} @ {{ $server['ip'] }}:{{ $server['port'] }}</h2>
            <div id="serverLine-{{ $server['id'] }}"></div>
            <div id="serverPie-{{ $server['id'] }}"></div>
            <div id="serverHist-{{ $server['id'] }}"></div>

            @linechart('ServerLine-' . $server['id'], 'serverLine-' . $server['id'])

            @linechart('ServerHist-' . $server['id'], 'serverHist-' . $server['id'])

            @piechart('ServerPie-' . $server['id'], 'serverPie-' . $server['id']);

        @endforeach


    </div>
    </div>
@endsection