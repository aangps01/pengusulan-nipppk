<script>
    @if (Session::has('error'))
        toastr.error('{{ Session::get('error') }}');
    @endif
    @if (Session::has('success'))
        toastr.success('{{ Session::get('success') }}');
    @endif
    @if ($errors->any())
        // toastr merge each error
        @foreach ($errors->all() as $error)
            toastr.info('{{ $error }}', 'Error', {
                closeButton: true,
                timeOut: 0,
                extendedTimeOut: 0,
            });
        @endforeach
    @endif
    @if (session('status'))
        toastr.info('{{ session('status') }}', 'Info', {
            closeButton: true,
            progressBar: true,
        });
    @endif
    @if (session('error-validation'))
        let message = '{{ session('error-validation')['message'] }}';
        let file = '{{ session('error-validation')['file'] }}';
        let anchor = '<a class="text-white text-decoration-underline" href="' + file + '" download>Download</a>';
        toastr.error(message + ' <br> ' + anchor, 'Error', {
            closeButton: true,
            timeOut: 0,
            extendedTimeOut: 0,
        })
    @endif
</script>
