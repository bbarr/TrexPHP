trex.User = function() {
	
	// check for cached instance, and return it
	if (trex.User._instance) {
		return trex.User._instance;
	}
	
	// this is where the users will be stored
	this.collection = {};
	
	// initial sync with server
	this.refresh();
	
	// cache instance
	trex.User._instance = this;
}

trex.User.prototype = {
	
	refresh : function() {
		var _this = this;
		rptr.ajax({
			uri : '/demo/api/user',
			callback : function(users, status) {
				_this.collection = users;
				rptr.publish('users_loaded');
			}
		});
	},
	
	add : function(name) {
		var _this = this;
		rptr.ajax({
			uri : '/demo/api/user',
			method : 'POST',
			data : {
				'name' : name
			},
			callback : function(response, status) {
				if (status === 201) {
					_this.collection[name] = { 'name' : name };
					rptr.publish('users_loaded');
				}
			}
		});
	},
	
	remove : function(name) {
		var _this = this;
		rptr.ajax({
			uri : encodeURI('/demo/api/user/' + name),
			method : 'DELETE',
			callback : function(response, status) {
				if (status === 200) {
					delete _this.collection[name];
					rptr.publish('users_loaded');
				}
			}
		});
		
	}
}

