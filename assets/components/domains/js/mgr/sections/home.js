Ext.onReady(function() {
	MODx.load({xtype: 'domains-page-home'});
});

Domains.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [];
	
	if (Domains.config.branding) {
		config.buttons.push({
			text 		: 'Domains ' + Domains.config.version,
			cls			: 'x-btn-branding',
			handler		: this.loadBranding
		});
	}
	
	config.buttons.push({
		text		: _('help_ex'),
		handler		: MODx.loadHelpPane,
		scope		: this
	});
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'domains-panel-home',
			renderTo	: 'domains-panel-home-div'
		}]
	});
	
	Domains.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Domains.page.Home, MODx.Component, {
	loadBranding: function(btn) {
		window.open(Domains.config.branding_url);
	}
});

Ext.reg('domains-page-home', Domains.page.Home);