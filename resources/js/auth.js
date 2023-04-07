$('.btn-toggle-password').on('click', function() {
    var $this = $(this);
    var $input = $this.closest('.input-group').find('input');
    var type = $input.attr('type') === 'password' ? 'text' : 'password';
    $input.attr('type', type);
    // toggle the class ic-eye-slash / ic-eye
    $this.toggleClass('isax-eye-slash isax-eye');
});
