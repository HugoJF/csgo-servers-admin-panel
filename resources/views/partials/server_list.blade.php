<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>IP</th>
            <th>Name</th>
            <th>Players</th>
            <th>Status</th>
            <th>Last Checked</th>
            <th>Manage</th>
        </tr>
        </thead>
        <tbody>

        @forelse($servers as $server)
            <tr>
                <td>
                    <a> {{ $server->ip }}:{{ $server->port }} </a>
                    <img width=18 height=18
                         src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDU2MSA1NjEiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2MSA1NjE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0iY29udGVudC1jb3B5Ij4KCQk8cGF0aCBkPSJNMzk1LjI1LDBoLTMwNmMtMjguMDUsMC01MSwyMi45NS01MSw1MXYzNTdoNTFWNTFoMzA2VjB6IE00NzEuNzUsMTAyaC0yODAuNWMtMjguMDUsMC01MSwyMi45NS01MSw1MXYzNTcgICAgYzAsMjguMDUsMjIuOTUsNTEsNTEsNTFoMjgwLjVjMjguMDUsMCw1MS0yMi45NSw1MS01MVYxNTNDNTIyLjc1LDEyNC45NSw0OTkuOCwxMDIsNDcxLjc1LDEwMnogTTQ3MS43NSw1MTBoLTI4MC41VjE1M2gyODAuNVY1MTAgICAgeiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo="/>
                </td>
                <td><a href="{{ route('servers', $server->id) }}">{{ $server->name }}</a></td>
                <td>{{ $server->stats()->latest()->first()->players }}/{{ $server->status()->latest()->first()->getMaxPlayers() }}</td>
                <td>
                    <img width=20 height=20
                         src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K"/>
                    @if($server->check()['online'] === true)
                        @if($server->check()['status'] === true)
                            <a style="color: green; font-weight: 600">{{ $server->check()['message'] }}</a>
                        @else
                            <a style="color: orange; font-weight: 600">{{ $server->check()['message'] }}</a>
                        @endif
                    @else
                        <a style="color: red; font-weight: 600">{{ $server->check()['message'] }}</a>
                    @endif

                </td>
                <td>{{ $server->stats()->latest()->first()->since() }}</td>
                <td>
                    <a href="{{ route('servers-manage', $server->id) }}" type="button" class="btn btn-default">Manage</a>
                </td>
            </tr>
        @empty
            <h2>No servers added</h2>
        @endforelse

        </tbody>
    </table>
</div>