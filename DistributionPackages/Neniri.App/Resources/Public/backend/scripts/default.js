$(function() {
    initLogin();
});

/*
* Base functions
* */
function initLogin() {
    let disableLoginButtonOnSubmit = function() {
        $('form').submit(function() {
            let buttonWapper = $('.button');
            let loginButton = buttonWapper.find('input');
            buttonWapper.addClass('loader');
            loginButton.val('').prop('disabled', true);
        });
    }

    disableLoginButtonOnSubmit();
}
