<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Goal Achieved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #555;
            line-height: 1.8;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            margin: 30px auto;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Congratulations, {{ $user->first_name }}!</h1>
    <p>You've achieved a reward: <strong>{{ $rewardTitle }}</strong>. This is a significant milestone, and we're thrilled to see you succeed!</p>
    <p>To view your rewards, click the button below:</p>
    <a href="{{ url('/rewards') }}" class="button">View Your Progress</a>
    <p>Thanks for being an awesome part of our community. Keep getting new rewards and pushing your limits!</p>
    <p>Warm regards,<br>{{ config('app.name') }}</p>
</div>
</body>
</html>

{{--@component('mail::message')--}}
{{--    # Congratulations, {{ $user->first_name }}!--}}

{{--    You've achieved your reward: **{{ $rewardTitle }}**. This is a significant milestone, and we're thrilled to see you succeed!--}}

{{--    @component('mail::button', ['url' => url('/achievements')])--}}
{{--        View Your Progress--}}
{{--    @endcomponent--}}

{{--    Thanks for being an awesome part of our community. Keep setting new rewards and pushing your limits!--}}

{{--    Warm regards,<br>--}}
{{--    {{ config('app.name') }}--}}
{{--@endcomponent--}}
