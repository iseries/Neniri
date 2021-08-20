// check if dom is ready
document.addEventListener('DOMContentLoaded', function() {
    initLogin();
});

/*
* Base functions
* */
function initLogin() {
    let disableLoginButtonOnClick = function() {
        let buttonWapper = document.querySelector('.button');
        let loginButton = buttonWapper.querySelector('input');
        loginButton.addEventListener('click', (e) => {
            e.preventDefault();
            buttonWapper.classList.add('loader');
            e.target.value = '';
            e.target.disabled = true;
        });
    }

    disableLoginButtonOnClick();
}
