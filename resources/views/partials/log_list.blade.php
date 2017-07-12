<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Message</th>
            <th>Log</th>
        </tr>
        </thead>
        <tbody>

        @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->message }}</td>
                <td>{{ str_replace("\n", '<br>', $log->log)}}</td>
            </tr>
        @empty
            <h2>No Logs found</h2>
        @endforelse

        </tbody>
    </table>
</div>