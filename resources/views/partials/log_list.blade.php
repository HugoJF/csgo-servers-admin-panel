<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Message</th>
            <th>Log</th>
            <th>Created at</th>
        </tr>
        </thead>
        <tbody>

        @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->type }}</td>
                <td>{{ $log->message }}</td>
                <td style="font-family: monospace">{!! str_replace("\n", '<br>', $log->log)!!}</td>
                <td>{{ $log->since() }}</td>
            </tr>
        @empty
            <h2>No Logs found</h2>
        @endforelse

        </tbody>
    </table>
</div>