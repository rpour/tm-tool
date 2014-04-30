$(function() {
    $(document).on('click', 'div[data-testcase="prepend"]', function() {
        $(this).parent().before($(this).parent().clone());
    });

    $(document).on('click', 'div[data-testcase="append"]', function() {
        $(this).before($(this).prev().clone());
    });

    $(document).on('click', 'div[data-testcase="delete"]', function() {
        if ($('div[data-testcase="delete"]').length > 1) {
            $(this).parent().remove();
        } else {
            alert('No');
        }
    });
});