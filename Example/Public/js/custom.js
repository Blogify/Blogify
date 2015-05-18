var app = {

    init: function()
    {
        app.notify.init();
    },

    /**
     * Let flash messages fade out
     *
     */
    notify: {

        init:function()
        {
            setTimeout(function()
            {
                $('#notify').fadeOut();
            }, 3000);
        }

    }

};

$(document.ready, app.init() );