(function() {

	// local place for app
	var app = trex.app;
	var users = new trex.User();

	app.route('/', function() {
		app.render('#demo-content', 'master:index', users.collection);
	});

	rptr.subscribe('.add_user', 'click', function(e) {
		e.preventDefault();
		users.add(app.element('#user_to_add').value);
	});

	rptr.subscribe('.remove_user', 'click', function(e) {
		e.preventDefault();
		users.remove(app.element('#user_to_remove').value);
	});	

	rptr.subscribe('users_loaded', function() {
		app.request('/');
		app.element('#user_to_remove').value = '';
		app.element('#user_to_add').value = '';
	});
	
})();
