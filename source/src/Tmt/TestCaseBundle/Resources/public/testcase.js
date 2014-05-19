$(function() {
    $(document).on('click', 'div[data-testcase="prepend"]', function() {
        $(this).parent().before(
            $(this).parent().clone().find("textarea").text("").end()
        );
    });

    $(document).on('click', 'div[data-testcase="append"]', function() {
        $(this).before(
            $(this).prev().clone().find("textarea").text("").end()
        );
    });

    $(document).on('click', 'div[data-testcase="delete"]', function() {
        if ($('div[data-testcase="delete"]').length > 1) {
            $(this).parent().remove();
        } else {
            alert('No');
        }
    });

    $('div[data-testcase="ok"]').click(function() {
        var testcase = $(this).closest('.testcase');
        testcase.removeClass('err').addClass('ok');
        testcase.find('.error').addClass('off');
        testcase.find('[data-case="passed"]').val(1);
        testcase.find('[data-case="error"]').text('');
    });

    $('div[data-testcase="err"]').click(function() {
        var testcase = $(this).closest('.testcase');
        testcase.removeClass('ok').addClass('err');
        testcase.find('.error').removeClass('off');
        testcase.find('[data-case="passed"]').val(1);
    });
});