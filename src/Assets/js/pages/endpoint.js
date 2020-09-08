$(document).ready(function () {

    //Init page handler

    pageHandler.init();
    
});

/**
 * Endpoint page class handler, responsible for UI interaction and functionality to the user listing on the frontend
 *
 * @author João Vieira
 * @license MIT
 */
var pageHandler = {

    /**
     * Initializes list and item
     *
     * @method init
     */
    init: function () {
    
        var self = this;
        
        //Init table
        
        self.list.init(self);
        
        //Init item
        
        self.item.init(self);
        
    },
    
    /**
     * Responsible for the listing loading, data population and error messages
     *
     * @method list
     */
    list: {
        
        /**
         * Initializes list view
         *
         * @param  object handler Parent object
         * @method init
         */
        init: function (handler) {
            
            var self = this;
            
            //Set vars
            
            self.handler = handler;
            
            self.element = '#userList';
            
            //Show table
        
            self.show();
            
        },
        
        /**
         * Request user list data from local endpoint
         *
         * @method show
         */
        show: function () {
            
            var self = this;

            //Get json
            
            $.getJSON('/inpsyde/api/users/list', function (response) {
                
                if (response.status == 'ok') {
                    self.populate(response.data);
                } else {
                    self.error();
                }
                
            })
            .fail(function () {
                
                //Raise error
                
                self.error();
            
            });
            
        },
        
        /**
         * Populates html table with local api data
         *
         * @param  array data Array of data provided from show method
         * @method show
         */
        populate: function (data) {
            
            var self = this;
            
            //Get table body
            
            var body = $(self.element).find('table > tbody');
            
            //Empty body
            
            body.empty();
            
            //Process rows
            
            $.each(data,function (key, row) {
                
                var html = '<tr>\
					<td><a href="#">'+row.id+'</a></td>\
					<td><a href="#">'+row.name+'</a></td>\
					<td><a href="#">'+row.username+'</a></td>\
					<td>'+row.email+'</td>\
					<td>'+row.address+'</td>\
					<td>'+row.phone+'</td>\
					<td>'+row.website+'</td>\
					<td>'+row.company+'</td>\
					<td><button class="btn btn-primary">View</button></td>\
				</tr>';
                
                var element = $(html);
                
                //Bind clicks
                
                element.find('a').each(function () {
                    $(this).click(function (e) {
                        
                        e.preventDefault();
                        
                        self.handler.item.show(row.id);
                        
                    });
                });
                
                element.find('button').click(function () {
                    self.handler.item.show(row.id);
                });
                
                body.append(element);
                
            });
            
        },
        
        /**
         * Shows error message
         *
         * @method show
         */
        error: function () {

            //Get table body
            
            var body = $(self.element).find('table > tbody');
            
            //Build html
            
            var html = '<tr>\
				<td scope="row" class="text-center" colspan="9">There was an error while loading the table!</td>\
			</tr>';
            
            //Empty table & append
            
            body.empty();
            body.append(html);
    
        }
        
    },
    
    /**
     * Responsible for the user details, loading, data population and user interaction
     *
     * @method item
     */
    item : {
        
        /**
         * Shows error message
         *
         * @method show
         */
        init: function (handler) {
            
            var self = this;
            
            //Set vars
            
            self.handler = handler;
            
            self.element = '#userDetails';
            
            //Bind return button on details
            
            $(self.element).find('button').click(function () {
                
                //Get elements
            
                var table = $(self.handler.list.element);
                var body = $(self.element);
            
                body.hide('slow',function () {
                    table.show('slow');
                });
            });
            
            //Bind return button on error
            
            $('#userError').find('button').click(function () {
                
                //Get elements
            
                var table = $(self.handler.list.element);
            
                $('#userError').hide('slow',function () {
                    table.show('slow');
                });
            });
            
        },
        
        /**
         * Request user item data from local api endpoint
         *
         * @method show
         */
        show: function (id) {
            
            var self = this;
            
            //Show loader
            
            self.handler.loader.show();

            //Get json
            
            $.getJSON('/inpsyde/api/users/item?id='+id, function (response) {
                
                //Hide loader
            
                self.handler.loader.hide();
                
                //Show response
                
                if (response.status == 'ok') {
                    self.populate(response.data);
                } else {
                    self.error();
                }
                
            })
            .fail(function () {
                
                //Raise error
                
                self.error();
            
            });
            
        },
        
        /**
         * Populates user page with local api data
         *
         * @param  array data Array of data provided from show method
         * @method populate
         */
        populate: function (data) {
            
            var self = this;
            
            //Get elements
            
            var table = $(self.handler.list.element);
            var body = $(self.element);
            
            //Clear all data fields
            
            body.find('[data-model]').html('');
            
            //Patch data into spans
            
            $.each(data,function (field, value) {
                body.find('[data-model="'+field+'"]').html(value);
            });
            
            //Show body
            
            table.hide('slow',function () {
                body.show('slow');
            });
            
        },
        
        /**
         * Shows error message
         *
         * @method show
         */
        error: function () {
            
            var self = this;

            //Hide table
            
            var table = $(self.handler.list.element).hide('slow');
            
            //Show error
            
            $('#userError').show('slow');
        }
    },
    
    /**
     * Shows / Hides a loading spinner
     *
     * @method loader
     */
    loader: {
        
        show: function () {
            
            $('#preloader').show();
            
        },
        
        hide: function () {
            
            $('#preloader').hide();
            
        }
    }
}