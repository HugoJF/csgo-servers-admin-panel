@extends('layouts.app')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="sub-header">{{ $server->name }} ({{ $server->ip }}:{{ $server->port }})</h1>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td width="40%" style="text-align:right;background-color: #f5f5f5;">Nome</td>
                        <td id="-state">{{ $server->name }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;background-color: #f5f5f5;">Current Players</td>
                        <td>{{ $server->stats()->latest()->first()->players }}/{{ $server->status()->latest()->first()->getMaxPlayers() }}</td>
                    </tr>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td width="40%" style="text-align:right;background-color: #f5f5f5;">IP</td>
                        <td id="-state">{{ $server->ip }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;background-color: #f5f5f5;">Last checked</td>
                        <td>{{ $server->stats()->latest()->first()->since() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <h2>Current players</h2>
        @include('partials.player_list', [
            'players' => $players
        ])

        <h2>Most recent Stats</h2>
        @include('partials.stats_list', [
            'stats' => $stats
        ])

        <h2>Most recent Status</h2>
        @include('partials.status_list', [
            'status' => $status
        ])

    </div>
@endsection