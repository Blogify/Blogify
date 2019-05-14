var app = {

    init: function()
    {
        app.sortable.init();
        app.delete.init();
        app.notify.init();
        app.ckedit.init();
        app.datetimepicker.init();
        app.slug.init();
        app.categories.init();
        app.tags.init();
        app.autoSave.init();
        app.protectedPosts.init();
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
         * Get an array with the current
         * datetime splited up.
         *
         * @returns {Array}
         */
        getDateTimeArray: function()
        {
            var current_date = new Date();
            var data = new Array();
            data['year'] = current_date.getFullYear();
            data['month'] = ((current_date.getMonth() + 1) < 10) ? '0' + (current_date.getMonth() + 1) : current_date.getMonth() + 1;
            data['day'] = (current_date.getDate() < 10) ? '0' + current_date.getDate() : current_date.getDate();
            data['hour'] = (current_date.getHours() < 10) ? '0' + current_date.getHours() : current_date.getHours();
            data['minutes'] = (current_date.getMinutes() < 10) ? '0' + current_date.getMinutes() : current_date.getMinutes();
            data['seconds'] = current_date.getSeconds();

            return data;
        },

        /**
         * Get the current date formatted
         * like it is stored in the DB
         *
         * @returns {string}
         */
        getDateTime: function()
        {
            var data = app.sortable.getDateTimeArray();

            return data['year'] + '-' + data['month'] + '-' + data['day'] + ' ' + data['hour'] + ':' + data['minutes'] + ':' + data['seconds'];
        },

        /**
         * Get a human readable date
         * from a given date
         *
         * @param fulldate
         * @returns {string}
         */
        getHumanReadableDatetime: function(fulldate)
        {
            var data = new Array();
            data['year'] = fulldate.getFullYear();
            data['month'] = ((fulldate.getMonth() + 1) < 10) ? '0' + (fulldate.getMonth() + 1) : fulldate.getMonth() + 1;
            data['day'] = (fulldate.getDate() < 10) ? '0' + fulldate.getDate() : fulldate.getDate();
            data['hour'] = (fulldate.getHours() < 10) ? '0' + fulldate.getHours() : fulldate.getHours();
            data['minutes'] = (fulldate.getMinutes() < 10) ? '0' + fulldate.getMinutes() : fulldate.getMinutes();
            data['day'] = (fulldate.getDate() < 10) ? '0' + fulldate.getDate() : fulldate.getDate();
            data['hour'] = (fulldate.getHours() < 10) ? '0' + fulldate.getHours() : fulldate.getHours();
            data['minutes'] = (fulldate.getMinutes() < 10) ? '0' + fulldate.getMinutes() : fulldate.getMinutes();

            return data['day'] + '-' + data['month'] + '-' + data['year'] + ' ' + data['hour'] + ':' + data['minutes'];
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
                app.sortable.appendData(data, link);
            } );
        },

        /**
         * Append the data the view
         *
         * @param data
         */
        appendData: function(data, atag)
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
                var row = '<tr>';

                append_data += row;

                for ( var n = 0; n < thead[0]['children'].length - 1; n++ )
                {
                    // Get the role attribute from the table head column
                    // this needs to be equal to the column name in the database
                    var columnName = thead[0]['children'][n].attributes[0].value;

                    if(columnName == 'created_at' || columnName == 'publish_date')
                    {
                        data['data'][i][columnName] = app.sortable.getHumanReadableDatetime(new Date(data['data'][i][columnName]));
                    }

                    if(columnName == 'role_id') {
                        data['data'][i][columnName] = data['data'][i]['name'];
                    }

                    if(columnName == 'status_id') {
                        data['data'][i][columnName] = data['data'][i]['name'];
                    }

                    append_data += "<td>" + data['data'][i][columnName] + "</td>";
                }

                var link            = $(atag);
                var url             = link[0].href;
                var urlParts        = url.split('/');
                var newUrl = app.generateBaseUrl() + '/' + '/admin/' + urlParts[6] + '/';

                if ( 'status_id' in data['data'][i] ) {
                    // Append the actions to the last column
                    append_data += "<td><a href='"+ newUrl + data['data'][i]['hash'] +'/edit' +"'><span class='fa fa-edit fa-fw'></span></a> <a href='"+ newUrl + data['data'][i]['hash'] +"'><span class='fa fa-eye fa-fw'></span></a> <form method='POST' action='"+ newUrl + data['data'][i]['hash'] +"' accept-charset='UTF-8' class='"+'form-delete '+ data['data'][i]['hash'] +"'><input name='_token' type='hidden' value='"+$('meta[name="_token"]').attr('content')+"'><input name='_method' type='hidden' value='delete'><a href='#' title='"+data['data'][i]['title']+"' class='delete' id='"+data['data'][i]['hash']+"'><span class='fa fa-trash-o fa-fw'></span></a></form></td>";
                }
                else {
                    // Append the actions to the last column
                    append_data += "<td><a href='"+ newUrl + data['data'][i]['hash'] +'/edit' +"'><span class='fa fa-edit fa-fw'></span></a> <form method='POST' action='"+ newUrl + data['data'][i]['hash'] +"' accept-charset='UTF-8' class='"+'form-delete '+ data['data'][i]['hash'] +"'><input name='_token' type='hidden' value='"+$('meta[name="_token"]').attr('content')+"'><input name='_method' type='hidden' value='delete'><a href='#' title='"+data['data'][i]['title']+"' class='delete' id='"+data['data'][i]['hash']+"'><span class='fa fa-trash-o fa-fw'></span></a></form></td>";
                }


                append_data += "</tr>";
            }

            // Append the sorted data to the table body
            tbody.append(append_data);
            app.delete.init();
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
                filebrowserUploadUrl: app.generateBaseUrl() + '/admin/posts/image/upload'
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
                $('.form-control-feedback').removeClass('hidden');
                app.slug.generateSlug();
            }, 1000));

            $('#slug').keyup(app.debounce(function(e){
                $('.form-control-feedback').removeClass('hidden');
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
                    'type': 'get',
                    'url': app.slug.apiBaseUrl,
                    'dateType': 'json'
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
            $('.form-control-feedback').addClass('hidden');
        }
    },

    /**
     * Categories management for
     * within the form for creating
     * a post
     *
     */
    categories: {

        /**
         * Check if we have to call the listener
         *
         */
        init:function()
        {
            if ( $('#create-category').length ) app.categories.listener();
        },

        /**
         * Listen to a click on the add button
         *
         */
        listener: function()
        {
            $("#create-category").on('click', function(e)
            {
                e.preventDefault();
                app.categories.handle();
            });
        },

        /**
         * Handle a given category
         *
         */
        handle: function()
        {
            $('#cat-form').removeClass('has-error');
            $('#cat-errors').empty();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']")[0].value
                },
                type:     'post',
                url:        app.generateBaseUrl() + '/admin/categories',
                data:       { 'name': $('#newCategory')[0].value },
                dataType:   'json',
                success: function(data)
                {
                    $('#category').append('<option selected value="'+data['hash'] +'">'+data['name']+'</option>');
                    $('#category').prop('disabled', false);
                    $('#no-cats-found').remove();
                    $('#newCategory')[0].value = '';
                },
                error: function(data)
                {
                    var errors = $('#cat-errors');
                    $('#cat-form').addClass('has-error');
                    errors.empty();
                    errors.append(data['responseJSON']['name'][0]);
                }
            });
        }

    },

    tags: {

        /**
         * Holds the added tags
         *
         */
        tags: [],

        /**
         * Check if we need to fill the tags array
         * and we have to start listening to the events
         *
         */
        init: function()
        {
            if ( $('#tag-btn').length )
            {
                app.tags.fillTagsArray();
                app.tags.listener();
                app.tags.tagDeleteListener();
                app.tags.prefill();
            }
        },

        /**
         *
         * Start listening to a click on the
         * add button
         *
         */
        listener: function()
        {
            $('#tag-btn').on('click', function() {
                app.tags.handle();
            });
        },

        /**
         * Handle a click on the add button
         *
         */
        handle: function()
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']")[0].value
                },
                type:     'post',
                url:        app.generateBaseUrl() + '/admin/tags',
                data:       { 'tags': $('#newTags')[0].value },
                dataType:   'json',
                success: function(data)
                {
                    if ( ! data['passed'] ) return app.tags.errors(data);

                    return app.tags.appendData(data);
                }
            });
        },

        /**
         * Handle validation errors
         *
         * @param data
         */
        errors: function(data)
        {
            var tagErrors = $("#tag-errors");
            tagErrors.empty();
            for (var i = 0; i < data['messages'].length; i++)
            {
                tagErrors.append('<p>' + data['messages'][i][0] + '</p>')
            }
        },

        /**
         * Append added tags to the view
         *
         * @param data
         */
        appendData: function(data)
        {
            for ( var i = 0; i < data['tags'].length; i++ )
            {
                if ( $('.' + data['tags'][i].hash).length <= 0 )
                {
                    $('#tags').append('<span class="tag '+ data['tags'][i].hash +'"><a href="#" class="'+ data['tags'][i].hash +'" title="Remove tag"><span class="fa fa-times-circle"></span></a> ' + data['tags'][i].name + ' ');
                    app.tags.appendTagToForm(data['tags'][i].hash);
                }
            }
            $('#newTags')[0].value = '';
            app.tags.fillTagsArray();
            app.tags.tagDeleteListener();
        },

        /**
         * Prefill the visible tags
         * that where all ready added
         * (when you have validation errors
         * these tags where added in the
         * background but where not visible for
         * the end user)
         *
         */
        prefill: function()
        {
            var tags = $('#addedTags')[0].value;
            var hashes = tags.split(',');

            $('tags').empty();

            for (var i = 0; i < hashes.length; i++)
            {
                $.ajax({
                    type:     'get',
                    url:        app.generateBaseUrl() + '/admin/api/tags/' + hashes[i],
                    dataType:   'json',
                    success: function(data)
                    {
                        if ( $('.' + data['hash']).length <= 0 )
                        {
                            $('#tags').append('<span class="tag '+ data['hash'] +'"><a href="#" class="'+ data['hash'] +'" title="Remove tag"><span class="fa fa-times-circle"></span></a> ' + data['name'] + ' ');
                        }
                        $('#newTags')[0].value = '';
                        app.tags.tagDeleteListener();
                    }
                });
            }
        },

        /**
         * Fill the global tags array
         * with the tags of the current post
         *
         */
        fillTagsArray: function()
        {
            var tags = $('#tags');
            for ( var i = 0; i < tags[0].children.length; i++ )
            {
                app.tags.tags.push(tags[0].children[i].children[0]['attributes'][1].nodeValue);
            }
        },

        /**
         * Start listening on a click
         * on the delete icon of a single tag
         *
         */
        tagDeleteListener: function()
        {
            for ( var i = 0; i < app.tags.tags.length; i ++ )
            {
                $('.' + app.tags.tags[i]).on('click', function(e, i){
                    app.tags.deleteTag(e);
                } );
            }
        },

        /**
         * Delete a tag from the view
         *
         * @param e
         */
        deleteTag: function(e)
        {
            e.preventDefault();
            var hash = e.currentTarget.className;
            $('.' + hash).remove();

            app.tags.deleteTagsFromForm(hash);
        },

        /**
         * Append the hash of an added
         * tag to the input field so we
         * have the data availbale after
         * submitting the form
         *
         * @param hash
         */
        appendTagToForm: function( hash )
        {
            var field = $('#addedTags')[0];
            field.value += (field.value == '') ? hash : ',' + hash;
        },

        /**
         * Delete a tag from the input field
         * when we delete a tag
         *
         * @param hash
         */
        deleteTagsFromForm: function ( hash ) {
            var field = $('#addedTags')[0];
            var value = field.value;
            field.value = value.replace(hash, '');
            field.value = field.value.replace(/,,/g, ',');
            if (field.value.indexOf(',') === 0)  field.value = field.value.replace(/,/g, '');
        }

    },

    /**
     * Automatically save a post
     * with an interval of X minutes
     *
     */
    autoSave: {

        /**
         * Holds the auto save interval
         * in minutes
         *
         */
        interval: 1,

        /**
         * Holds the data that needs
         * to be auto saved
         *
         */
        data: {},

        /**
         * Define if we have to set an
         * interval and call the handler
         * or not
         *
         */
        init: function()
        {
            if ( $('#post').length )
            {
                setInterval(function() {
                    app.autoSave.fillDataObject();
                    app.autoSave.handler();
                }, 1000 * 60 * app.autoSave.interval );
            }
        },

        /**
         * Get the values of the input fields
         * and place them in the data object
         *
         */
        fillDataObject: function()
        {
            app.autoSave.data = {
                title: $('#title')[0].value,
                highlight: $('#highlight option:selected')[0].value,
                slug: $('#slug')[0].value,
                content: CKEDITOR.instances.post.getData(),
                status: $('#status')[0].value,
                visibility: $('#visibility')[0].value,
                publishdate: $('#publishdate')[0].value,
                reviewer: $('#reviewer')[0].value,
                category: $('#category')[0].value,
                tags: $('#addedTags')[0].value
            }
        },

        /**
         * Make the auto save request
         *
         */
        handler: function()
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']")[0].value
                },
                type:     'post',
                url:        app.generateBaseUrl() + '/admin/api/autosave',
                data:       app.autoSave.data,
                dataType:   'json',
                success: function( response )
                {
                    if ( response[0] )
                    {
                        $('.auto-save-log').empty();
                        $('.auto-save-log').append('<p><span> Last saved on '+ response[1] +'</span></p>');
                    }
                    else
                    {
                        $('.auto-save-log').empty();
                        $('.auto-save-log').append('<p><span class="text-danger"> Faild to save on '+ response[1] +'</span></p>');
                    }
                }
            });
        }
    },

    protectedPosts: {

        init: function()
        {
            if ( $('#post').length )
            {
                app.protectedPosts.listener();
                if ( $('#visibility')[0].selectedOptions[0].text == 'Protected' ) $('#password-protected-post').css('display', 'block');
            }
        },

        listener: function()
        {
            $('#visibility').on('change', function(e){
                if(e.currentTarget.selectedOptions[0].text == 'Protected')
                {
                    $('#password-protected-post').css('display', 'block');
                }
                else
                {
                    $('#password-protected-post').css('display', 'none');
                }
            });
        }

    }

};

$(document.ready, app.init() );