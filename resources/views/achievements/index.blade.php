<x-app-layout :assets="$assets ?? []">
    <style>
        .no-rewards-container {
            text-align: center;
            margin-top: 50px;
        }
        .no-rewards-icon {
            opacity: 0.5;
            width: 100px; /* Adjust the size as needed */
            height: auto;
            margin-bottom: 20px;
        }
    </style>
    <h1>Rewards for {{ $userName }}</h1>
    @if($achievements->isEmpty())
        <div class="no-rewards-container">
            <img src="{{ asset('images/badges/TROPHY.svg') }}"  alt="No Achievements" class="no-rewards-icon">
            <p>No achievements found.</p>
        </div>
    @else
        <div class="row">

            @foreach ($achievements as $achievement)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $achievement->goal->title   }}</h5>
                            <img src="{{ asset('images/svg/goal.svg') }}" alt="Reward Icon" class="card-img-top">
                            <p class="card-text">Achieved: {{ $achievement->created_at->format('m/d/Y') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
