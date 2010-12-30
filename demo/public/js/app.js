(function() {
	
	var app = new velo.Application('demo');
	
	app.route('/', function() {
		
		rptr.ajax({
			uri : '/demo/api/user/1',
			success : function(data) {
				app.render('#demo', 'index', data);
			}
		});
		
	});
	
	app.views.add({
		index : function(users) {
			return rptr.build('div', [
				rptr.build('h3', 'List of users:'),	
				function(div) {
					for (var i = 0, len = users.length; i < len; i++) {
						div.appendChild(rptr.build('p', users.name))
					}
				}
			]);
		}
	});
	
	app.start();
})();