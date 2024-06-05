<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta x-ref="csrf_token" name="csrf-token" content="{{csrf_token()}}">

        <title>{{ config('app.name') }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="shortcut icon" href="/images/logo-mantools.png" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body>
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
            <!-- Desktop sidebar -->
            @include('includes.desktop-sidebar')

            <!-- Mobile sidebar -->
            @include('includes.mobile-sidebar')

            <div class="flex flex-col flex-1 w-full">
                @include('includes.header')
                <main class="h-full overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>

        <script src="/js/main-theme.js"></script>
        <script type="module">
            @if(Session::has('flash'))
                @php list($type, $title, $message) = explode('|', Session::get('flash')) @endphp
                Swal.fire({
                    title: '{{$title}}',
                    text: '{{$message}}',
                    icon: '{{$type}}',
                    showConfirmButton: false
                })
            @endif

            $('#btn_reject_step').click(function() {
                Swal.fire({
                    title: 'Reject',
                    text: 'Apakah Anda yakin akan reject?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if(result.isConfirmed) submitFinal($('#note_step').val(), 'Reject')
                })
            })

            $('#btn_approve_step').click(function() {
                Swal.fire({
                    title: 'Approve',
                    text: 'Apakah Anda yakin akan approve?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if(result.isConfirmed) submitFinal($('#note_step').val(), 'Approve')
                })
            })

            $('#btn_continue_step').click(function() {
                Swal.fire({
                    title: 'Lanjutkan Proses',
                    html: 'Lanjutkan proses akan merubah status menjadi <b>Menunggu Verifikasi QC</b>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if(result.isConfirmed) submitFinal($('#note_step').val(), null, true)
                })
            })
            
            removeValidationError()

            function removeValidationError() {
                $(document).on('keyup paste', 'input[type=text], input[type=number], textarea', function() {
                    let parent_class = $(this).parent().prop('className')
                    if(parent_class == 'input-group') {
                        $(this).parent().siblings('.validation_error').remove()
                    } else {
                        $(this).siblings('.validation_error').remove()
                    }
                })

                $(document).on('change', 'select, input[type=date], input[type=time], input[type=file]', function() {
                    $(this).siblings('.validation_error').remove()
                })
            }

            function submitFinal(note, status, is_continue_step = false) {
                let maintenance_result_unique_code = $('#maintenance_result_unique_code').val()

                $.LoadingOverlay('show')
                $('#btn_reject_step').prop('disabled', true)
                $('#btn_approve_step').prop('disabled', true)
                $('#btn_continue_step').prop('disabled', true)

                fetch(`/qc/${maintenance_result_unique_code}/final`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        note: note,
                        status: status
                    })
                })
                .then(response => {
                    $.LoadingOverlay('hide')

                    if(response.status != 200) {
                        return Promise.reject(response)
                    }
                    Swal.fire({
                        title: 'Berhasil',
                        text: is_continue_step ? 'Melanjutkan proses' : `${status}`,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        location.reload()
                    })
                })
                .catch(error => {
                    $.LoadingOverlay('hide')
                    $('#btn_reject_step').prop('disabled', false)
                    $('#btn_approve_step').prop('disabled', false)
                    $('#btn_continue_step').prop('disabled', false)

                    let status = error.status
                    let statusText = error.statusText

                    if(status == 422) {
                        error.json().then(json => {
                            let index = 0
                            $.each(json.errors, function(key, value) {
                                $(`.${key}`).siblings('.validation_error').remove()
                                $(`.${key}`).parent().append(`<span class="validation_error text-red-500">${value.join('<br>')}</span>`)

                                if(index == 0) {
                                    $(`.${key}`).parent().get(0).scrollIntoView({behavior: 'smooth'})
                                }

                                index++
                            })
                        })
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonText: 'Ok'
                        })
                    }
                })
            }
        </script>
        @stack('js')
    </body>
</html>