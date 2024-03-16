<x-app-layout :assets="$assets ?? []">

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Notifications</h4>
                </div>

                <div class="card-action">

                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table text-center table-striped w-100">
                        <thead>
                        <tr>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($notifications as $notification)
                            <tr class="{{ $notification->read_at ? '' : 'font-bold' }}">
                                <td>{{ $notification->data['message'] }}</td>
                                <td>{{ $notification->created_at->diffForHumans() }}</td> <!-- Format the date -->
                                <td>{{ $notification->read_at ? 'Read' : 'Unread' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
