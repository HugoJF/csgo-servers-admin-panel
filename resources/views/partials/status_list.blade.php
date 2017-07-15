<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Hostname</th>
            <th>Version</th>
            <th>UDP/IP</th>
            <th>OS</th>
            <th>Type</th>
            <th>Map</th>
            <th>Since</th>
        </tr>
        </thead>
        <tbody>

        @forelse($status as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td><a href="{{ route('servers-manage', $s->server_id) }}">{{ $s->hostname }}</a></td>
                <td>{{ $s->version }}</td>
                <td>{{ $s->udpip }}</td>
                <td>{{ $s->os }}</td>
                <td>{{ $s->type }}</td>
                <td>{{ $s->map }}</td>
                <td>{{ $s->since() }}</td>
            </tr>
        @empty
            <h2>No Status found</h2>
        @endforelse

        </tbody>
    </table>
</div>