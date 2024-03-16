<x-app-layout :assets="$assets ?? []">


<div>
    <span>Notification(s).</span>
    <ul>
        @foreach ($notifications as $notification)
            <li class="{{ $notification->read_at ? '' : 'font-bold' }}">
                {{ $notification->data['message'] }} <!-- Display the message -->
                <!-- Add more details from $notification->data as needed -->
            </li>
        @endforeach
    </ul>
</div>
</x-app-layout>
