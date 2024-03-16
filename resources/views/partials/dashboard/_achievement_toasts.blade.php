
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toast-body">
                Hello, world! This is a toast message.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', (event) => {
        // Check for 'achievement' in the session
        @if (Session::has('achievement'))
        var toastLiveExample = document.getElementById('liveToast');
        var toastBody = document.getElementById('toast-body');

        // Set the dynamic message from the session
        toastBody.textContent = "{{ Session::get('achievement') }}";
        var toast = new bootstrap.Toast(toastLiveExample);

        toast.show();
        @endif
    });
</script>
