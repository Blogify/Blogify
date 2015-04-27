var app = {

    init: function()
    {
        app.sortable.init();
        app.delete.init();
        app.notify.init();
        app.ckedit.init();
        app.datetimepicker.init();
        app.slug.init();
    },

    /**
     * Delay javascript events to
     * decrease the number of function calls
     *
     * I take no credit for this code
     * Source: http://davidwalsh.name/javascript-debounce-function
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
     * Extract the base url
     * from the current url
     *
     */
    generateBaseUrl: function()
    {
        var pathArray = location.href.split( '/' );
        var protocol = pathArray[0];
        var host = pathArray[2];
        var url = protocol + '//' + host;
        return url;
    },

    /**
     * Sort the data of a model on column name
     *
     */
    sortable: {
        url: '',

        /**
         * Listen to a click on a sortable element
         *
         */
        init: function()
        {

            $('.sort').on('click', function(e){
                e.preventDefault();
                app.sortable.fetchData(this);
                app.sortable.changeLink(this);
            });

        },

        /**
         * Fetch the new data
         *
         * @param link
         */
        fetchData: function( link )
        {
            app.sortable.url = link.href;

            $.ajax( {
                url: app.sortable.url,
                dataType: 'json'
            } ).done( function( data ) {
                app.sortable.appendData(data);
            } );
        },

        /**
         * Append the data the view
         *
         * @param data
         */
        appendData: function(data)
        {
            // get the columns of the table head
            var thead = $('.sortable thead tr');

            // get the table body
            var tbody = $('.sortable tbody');

            // Holds the data that will be appended to the view
            var append_data = "";

            // Empty the table body
            tbody.empty();

            // Loop through the results
            for ( var i = 0; i < data['data'].length; i++ )
            {
                append_data += "<tr>";

                for ( var n = 0; n < thead[0]['children'].length - 1; n++ )
                {
                    // Get the role attribute from the table head column
                    // this needs to be equal to the column name in the database
                    var columnName = thead[0]['children'][n].attributes[0].value;

                    append_data += "<td>" + data['data'][i][columnName] + "</td>";
                }

                // Append the actions to the last column
                append_data += "<td><a href='#'><span class='fa fa-edit fa-fw'></span></a> <a href='#'><span class='fa fa-trash-o fa-fw'></span></a></td>";

                append_data += "</tr>";
            }

            // Append the sorted data to the table body
            tbody.append(append_data);
        },

        /**
         * Change the table head link to it's inverse
         * asc  => desc
         * desc => asc
         *
         * @param atag
         */
        changeLink: function( atag )
        {
            var link            = $(atag);
            var url             = link[0].href;
            var urlParts        = url.split('/');
            var urlPartsLength  = urlParts.length;
            var order           = urlParts[urlPartsLength-2];
            var newUrl          = "";

            if (order == "asc")
            {
                order = "desc";
                $(".fa.fa-sort-up.fa-fw").remove();
                $(".fa.fa-sort-down.fa-fw").remove();
                link.append(' <span class="fa fa-sort-down fa-fw"></span>')
            }
            else
            {
                order = "asc";
                $(".fa.fa-sort-up.fa-fw").remove();
                $(".fa.fa-sort-down.fa-fw").remove();
                link.append(' <span class="fa fa-sort-up fa-fw"></span>')
            }

            for ( var i = 2; i < urlPartsLength - 2; i++ )
            {
                newUrl += (i ==2) ? urlParts[i] : "/" + urlParts[i];
            }

            newUrl += '/' + order;

            link[0].href = "http://" + newUrl + '/' + urlParts[urlPartsLength - 1];
        }
    },

    delete: {

        init: function()
        {
            $('.delete').on('click', function(e)
            {
                e.preventDefault();

                if ( ! confirm('Are you sure you want to delete ' + this.title + ' ?') ) return false;

                $('form.' + this.id).submit();
            });
        }

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

    },

    /**
     * WYSIWYG
     *
     */
    ckedit: {

        /**
         * Check if we need to initialize
         * the wysiwyg
         *
         */
        init:function()
        {
            if ( $('#post').length )
            {
                app.ckedit.configure();
            }
        },

        /**
         * Initialize and configure the
         * wysiwyg
         *
         */
        configure: function()
        {
            CKEDITOR.config.height = 400;
            CKEDITOR.config.extraPlugins = 'wordcount';
            CKEDITOR.replace( 'post',{
                filebrowserUploadUrl: 'http://eindwerk.app:8000/admin/posts/image/upload/'
            } );
        }
    },

    /**
     * Date time picker
     *
     */
    datetimepicker: {

        /**
         * Check if we need to initialize
         * the date time picker
         *
         */
        init:function()
        {
            if ( $('#dtBox').length )
            {
                app.datetimepicker.configure();
            }
        },

        /**
         * Initialise and configure the
         * date time picker
         *
         */
        configure: function()
        {
            $("#dtBox").DateTimePicker({
                'titleContentDateTime': 'Set the publish date and time',
                addEventHandlers: function()
                {
                    var dtPickerObj = this;
                    dtPickerObj.setDateTimeStringInInputField();
                }
            });
        }
    },

    /**
     * Auto fill in the slug field of a post
     * and check if it is a unique one
     *
     */
    slug: {
        slug: '',
        apiBaseUrl: '',

        /**
         * Check if the listener has to be called
         *
         */
        init: function()
        {
            if ( $('#title').length && $('#slug').length ) app.slug.listener();
        },

        /**
         * Listen to a keyup on the title
         * and slug field
         *
         */
        listener: function()
        {
            $('#title').keyup(app.debounce(function(e){
                app.slug.generateSlug();
            }, 1000));

            $('#slug').keyup(app.debounce(function(e){
                app.slug.slug = $('#slug')[0].value;
                app.slug.slug = app.slug.slug.replace(/ /g,"-").toLowerCase();
                app.slug.checkIfSlugIsUnique();
            }, 1000));
        },

        /**
         * Generate a valid slug
         *
         */
        generateSlug: function()
        {
            app.slug.slug = $('#title')[0].value;
            app.slug.slug = app.slug.slug.replace(/ /g,"-").toLowerCase();
            app.slug.checkIfSlugIsUnique();
        },

        /**
         * Check if the generated/given slug is unique
         *
         */
        checkIfSlugIsUnique: function()
        {
            app.slug.apiBaseUrl = app.generateBaseUrl() + '/admin/api/slug/checkIfSlugIsUnique/' + app.slug.slug;
            if ( app.slug.slug.length > 0 )
            {
                $.ajax({
                    'method': 'get',
                    'url': app.slug.apiBaseUrl,
                    'type': 'json'
                }).done( function( data ) {
                    app.slug.fillSlugField(data);
                } );
            }
        },

        /**
         * Fill in the slug field
         *
         * @param slug
         */
        fillSlugField: function( slug )
        {
            $('#slug')[0].value = slug;
        }
    }

};

$(document.ready, app.init() );