$(document).ready(function() {
    $('.button-select-all').on('click', function () {
        $('input[name^="person"]').each(function() {
            this.checked = true;
        });

        $('a.button-select-all').addClass('hidden');
        $('a.button-unselect-all').removeClass('hidden');
    });

    $('.button-unselect-all').on('click', function () {
        $('input[name^="person"]').each(function() {
            this.checked = false;
        });

        $('a.button-unselect-all').addClass('hidden');
        $('a.button-select-all').removeClass('hidden');
    });
});
