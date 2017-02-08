var Domains = function(config) {
	config = config || {};
	
	Domains.superclass.constructor.call(this, config);
};

Ext.extend(Domains, Ext.Component, {
	page	: {},
	window	: {},
	grid	: {},
	tree	: {},
	panel	: {},
	combo	: {},
	config	: {}
});

Ext.reg('domains', Domains);

Domains = new Domains();