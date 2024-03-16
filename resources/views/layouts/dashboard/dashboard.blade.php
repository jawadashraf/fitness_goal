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
                appId: "7801ce52-4a89-457c-a6f5-0f9164ed13c7",
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

        // OneSignalDeferred.push(async function (OneSignal) {
        //     let start = +(new Date());
        //     let interval = setInterval(() => {
        //         let now = +(new Date());
        //         console.log(`${(now-start)}ms timeout: `, OneSignal.User.pushSubscription.id);
        //
        //         if (OneSignal.User.pushSubscription.id) {
        //             clearInterval(interval);
        //         }
        //
        //         // Do work...
        //     }, 1000);
        // });




        // OneSignalDeferred.push(async function (OneSignal) {
        //     OneSignal.push(() => {
        //         OneSignal.isPushNotificationsEnabled(isEnabled => {
        //             if (isEnabled) {
        //                 // user has subscribed
        //                 OneSignal.getUserId(userId => {
        //                     console.log(`player_id of the subscribed user is : ${userId}`)
        //                     // Make a POST call to your server with the user ID
        //                 })
        //             }
        //         })
        //     });
        //     // OneSignal.Slidedown.promptPush({force: true});
        // });
    </script>

</head>
<body class="" >
@include('partials.dashboard._body')
</body>

</html>
