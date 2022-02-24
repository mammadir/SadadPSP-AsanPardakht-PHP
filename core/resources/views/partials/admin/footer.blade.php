<script src="{{ asset('assets/libs/jquery/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/libs/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script>
  $('.btn-popup').click(function (e) {
    e.preventDefault();
    popup($(this).attr('href'), '', 900, 800);
  });
</script>
@stack('scripts')