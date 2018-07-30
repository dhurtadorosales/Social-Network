$(function () {
    var $lblNotifications = $('.label-notifications');
    var $lblMessages = $('.label-messages');

    showHideNbNotificationsAndMessages();

    //Get number of notifications and not read messages
    notificationsAndMessages();

    setInterval(function () {
        notificationsAndMessages();
    }, 60000);

    /**
     *
     */
    function notificationsAndMessages() {
        $.ajax({
            url: URL + '/notifications/count',
            type: 'GET',
            success: function (response) {
                $lblNotifications.html(response)

                showHideNbNotificationsAndMessages();
            }
        });

        $.ajax({
            url: URL + '/messages/count',
            type: 'GET',
            success: function (response) {
                $lblMessages.html(response)

                showHideNbNotificationsAndMessages();
            }
        });
    }

    /**
     *
     */
    function showHideNbNotificationsAndMessages() {
        if ($lblNotifications.text() == 0) {
            $lblNotifications.addClass('hidden');
        } else {
            $lblNotifications.removeClass('hidden');
        }

        if ($lblMessages.text() == 0) {
            $lblMessages.addClass('hidden');
        } else {
            $lblMessages.removeClass('hidden');
        }
    }
});
