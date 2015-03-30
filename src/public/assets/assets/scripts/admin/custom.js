var app = {

    init: function()
    {
        app.sortable.init();
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
    }

};

$(document.ready, app.init() );