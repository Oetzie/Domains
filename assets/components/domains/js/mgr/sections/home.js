Ext.onReady(function() {
	MODx.load({xtype: 'domains-page-home'});
});

Domains.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [{
		text		: _('help_ex'),
		handler		: MODx.loadHelpPane,
		scope		: this
	}];
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'domains-panel-home',
			renderTo	: 'domains-panel-home-div'
		}]
	});
	
	Domains.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Domains.page.Home, MODx.Component);

Ext.reg('domains-page-home', Domains.page.Home);