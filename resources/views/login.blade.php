<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login | {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="shortcut icon" href="/images/logo.jpg" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50">
        <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl">
            <div class="flex flex-col overflow-y-auto md:flex-row">
                <div class="h-32 md:h-auto md:w-1/2">
                    <img aria-hidden="true" class="object-cover w-full h-full"
                        src="/images/logo.jpg" />
                </div>
                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <div class="w-full">
                        <h1 class="mt-4 w-full text-xl font-semibold text-gray-700">
                            Login {{ config('app.name') }}
                        </h1>
                        <form action="{{ route('login') }}" method="post" class="mt-4">
                            @csrf
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Username</span>
                                </label>
                                <input type="text" class="input input-bordered input-sm" autocomplete="off" name="username" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Password</span>
                                </label>
                                <label class="input-group">
                                    <input type="password" id="password" class="input input-bordered input-sm w-full" autocomplete="off" name="password" required>
                                    <span id="toggle_password"><i class="ti ti-eye-off"></i></span>
                                </label>
                            </div>
                            @error('username')
                                <div class="validation_error block text-red-600">{{ $message }}</div>
                            @enderror
                            <button type="submit"
                                class="btn block w-full mt-4 text-white capitalize bg-gray-800 hover:bg-gray-950">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(function() {
            $(document).on('keyup paste', 'input[type=text]', function() {
                $('.validation_error').remove()
            })
            
            $('#toggle_password').on('click', function() {
                let password = $('#password')
                let field_type = password.attr('type')

                if (field_type == 'password') {
                    password.attr('type', 'text')
                    $(this).html('<i class="ti ti-eye"></i>')
                } else {
                    password.attr('type', 'password')
                    $(this).html('<i class="ti ti-eye-off"></i>')
                }
            })
        })
    </script>
</body>

</html>
