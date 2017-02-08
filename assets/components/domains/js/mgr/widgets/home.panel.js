Domains.panel.Home = function(config) {
	config = config || {};
	
    Ext.apply(config, {
        id			: 'domains-panel-home',
        cls			: 'container',
        defaults	: {
        	collapsible	: false,
        	autoHeight	: true,
        	border 		: false
        },
        items		: [{
            html		: '<h2>'+_('domains')+'</h2>',
            id			: 'domains-header',
            cls			: 'modx-page-header'
        }, {
        	layout		: 'form',
        	border 		: true,
            defaults	: {
            	autoHeight	: true,
            	border		: false
            },
            items		: [{
            	html			: '<p>' + _('domains.domains_desc') + '</p>',
                bodyCssClass	: 'panel-desc'
            }, {
                xtype			: 'domains-grid-domains',
                cls				: 'main-wrapper',
                preventRender	: true
            }]
        }]
    });

	Domains.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(Domains.panel.Home, MODx.FormPanel);

Ext.reg('domains-panel-home', Domains.panel.Home);