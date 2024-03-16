@props(['dir'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ mighty_language_direction() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME')}}</title>

    @include('partials.dashboard._head')

    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function (OneSignal) {
            OneSignal.init({
                appId: "{{ env('ONESIGNAL_APP_ID') }}",
                notifyButton: {enable: true}
            });

            OneSignal.push(function() {
                let interval = setInterval(async ()=>{
                    let subscriptionId = await OneSignal.User.PushSubscription.id
                    console.log(subscriptionId, "onesignal subscription id after login")
                    if(subscriptionId !== null && subscriptionId !== undefined){
                        console.log('enter inside interval loop')
                        let req = {
                            device_id: subscriptionId
                        }

                        // Send the device_id to the server
                        fetch('/save-player-id', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Ensure CSRF token is sent with request
                            },
                            body: JSON.stringify(req)
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Player ID saved:', data);
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                            });

                        clearInterval(interval);
                    }
                }, 1000)
            });

        });

    </script>

</head>
<body class="" >
@include('partials.dashboard._body')
</body>

</html>
