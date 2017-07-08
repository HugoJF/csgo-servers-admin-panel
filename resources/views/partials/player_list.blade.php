<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>UserID</th>
            <th>Name</th>
            <th>UniqueID</th>
            <th>Connected</th>
            <th>Ping</th>
            <th>Loss</th>
            <th>State</th>
            <th>Rate</th>
            <th>Adr</th>
        </tr>
        </thead>
        <tbody>

        @forelse($players as $player)
            <tr>
                <td>{{ $player->id }}</td>
                <td>{{ $player->userid}}</td>
                <td><a href="{{ route('players-name-history', $player->id) }}">{{ $player->name }}</a></td>
                <td>{{ $player->uniqueid }}</td>
                <td>{{ $player->connected }}</td>
                <td>{{ $player->ping }}</td>
                <td>{{ $player->loss }}</td>
                <td>{{ $player->state }}</td>
                <td>{{ $player->rate }}</td>
                <td>{{ $player->adr }}</td>
            </tr>
        @empty
            <h2>No Status found</h2>
        @endforelse

        </tbody>
    </table>
</div>