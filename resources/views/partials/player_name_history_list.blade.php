<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Created at</th>
        </tr>
        </thead>
        <tbody>

        @forelse($player->nameHistory()->latest()->get() as $name)
            <tr>
                <td>{{ $name->id }}</td>
                <td>{{ $name->name }}</td>
                <td>{{ $name->created_at }}</td>
            </tr>
        @empty
            <h2>No name history found</h2>
        @endforelse

        </tbody>
    </table>
</div>