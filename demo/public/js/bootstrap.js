/**
 *  Bootstrap file
 *  - setup namespace and some essential properties
 *  - include js components
 */

// namespace
var trex = {};

// new application
trex.app = new velo.Application('demo');

// instance of template, for views
trex.template = new rptr.tools.Template();

// set default script location
rptr.config.scripts.set_base_path('/demo/public/js/');

// load controller, user model, and views.. then the app is ready!
rptr.require(['models/user', 'controller', 'views'], function() {
	trex.app.start();
});