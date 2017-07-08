<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>NetIn</th>
            <th>NetOut</th>
            <th>Uptime</th>
            <th>Maps</th>
            <th>FPS</th>
            <th>Players</th>
            <th>Svms</th>
            <th>Svms/stdv</th>
            <th>var</th>
        </tr>
        </thead>
        <tbody>

        @forelse($stats as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->netin }}</td>
                <td>{{ $s->netout }}</td>
                <td>{{ $s->uptime }}</td>
                <td>{{ $s->maps }}</td>
                <td>{{ $s->fps }}</td>
                <td>{{ $s->players }}</td>
                <td>{{ $s->svms }}</td>
                <td>{{ $s->svms_stdv }}</td>
                <td>{{ $s->var }}</td>
            </tr>
        @empty
            <h2>No servers added</h2>
        @endforelse

        </tbody>
    </table>
</div>