$(function () {
    $('#registrationPanelLableId').click(function () {
        $('#registrationContener').slideDown(500);
        $('#loginContaner').slideUp(500);
        $('#forgotPasswordContener').slideUp(500);
    });

    $('#loginPanelLableId').click(function () {
        $('#registrationContener').slideUp(500);
        $('#loginContaner').slideDown(500);
        $('#forgotPasswordContener').slideUp(500);
    });

    $('#forgotPassword').click(function () {
        $('#registrationContener').slideUp(500);
        $('#loginContaner').slideUp(500);
        $('#forgotPasswordContener').slideDown(500);
    });
});
