(function() {
	
	var app = trex.app;
	var template = trex.template;
	
	app.views.add({

		partials : {

			actions : function() {
				template
					.ul_({'id' : 'actions'})
						.li_()
							.input({'id' : 'user_to_add', 'type' : 'text'})
							.a_({'href' : '/', 'class' : 'add_user'})
								.t('Add User')
							.c()
						.c()
						.li_()
							.input({'id' : 'user_to_remove', 'type' : 'text'})
							.a_({'href' : '/', 'class' : 'remove_user'})
								.t('Remove User')
							.c(3)

				return template.dump();
			}
		},

		layouts : {

			master : function() {

				var actions = app.views.process('partials/actions');

				template
					.embed(actions)
					.div({'id' : 'demo-content'})

				return template.dump();
			}
		},

		index : function(users) {
			return rptr.build('div', [
				rptr.build('h3', 'List of users:'),	
				function(div) {
					for (var user in users) {
						div.appendChild(rptr.build('p', user))
					}
				}
			]);
		}
	});
	
})();