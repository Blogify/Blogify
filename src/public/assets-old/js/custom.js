/**
 * Sidebar object
 *
 * @type {{items: *, links: Array, headerNav: (*|jQuery|HTMLElement), init: Function, addToMobileNav: Function, deleteFromMobileNav: Function, debounce: Function, checkWindowSize: Function}}
 */
var sidebar = {
    items:      $(".col2 ul.nav.nav-pills.nav-stacked")[0].children,
    links:      [],
    headerNav:  $(".navbar-collapse ul.nav.navbar-nav.left-nav"),

    init: function()
    {
        // Loop through the sidebar items and place them in an array
        for(var i = 0; i < sidebar.items.length; i++)
        {
            sidebar.links[i] = sidebar.items[i].innerHTML;
        }

        // Check the window size on document ready
        sidebar.checkWindowSize();

        // Listen to the window resize with an debounce on it
        $(window).resize(sidebar.debounce(function(e) {
            sidebar.checkWindowSize();
        },200));


    },

    /**
     * Add the sidebar nav items to the main nav for mobile
     *
     */
    addToMobileNav: function()
    {
        for ( var i = 0; i < sidebar.links.length; i++)
        {
            $(sidebar.headerNav).append('<li class="side">' + sidebar.links[i] + '</li>');
        }
    },

    /**
     * Delete the sidebar nav items form the main nav for desktop
     *
     */
    deleteFromMobileNav: function()
    {
        $(".side").remove();
    },

    /**
     * Debounce function
     * I take no credit for this one!
     *
     * @param fn
     * @param delay
     * @returns {Function}
     */
    debounce: function(fn, delay)
    {
        var timer = null;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    },

    /**
     * Check the current window size
     *
     */
    checkWindowSize: function()
    {
        if ($(window).width() <= "768") {
            sidebar.deleteFromMobileNav();
            sidebar.addToMobileNav();
        }
        else {
            sidebar.deleteFromMobileNav();
        }
    }
};

$( function() { sidebar.init(); } );

