@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/summernote/summernote-lite.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/libs/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $('{{ $element }}').summernote({
            height: 200,
            lang: 'fa-IR'
        });
    </script>
@endpush