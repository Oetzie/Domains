Domains.grid.Domains = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('domains.domain_create'),
        cls         : 'primary-button',
        handler     : this.createDomain,
        scope       : this
    }, '->', {
        xtype       : 'textfield',
        name        : 'domains-filter-search',
        id          : 'domains-filter-search',
        emptyText   : _('search') + '...',
        listeners   : {
            'change'    : {
                fn          : this.filterSearch,
                scope       : this
            },
            'render'    : {
                fn          : function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        keys    : Ext.EventObject.ENTER,
                        fn      : this.blur,
                        scope   : cmp
                    });
                },
                scope       : this
            }
        }
    }, {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'domains-filter-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];
    
    var expander = new Ext.grid.RowExpander({
        getRowClass : function(record, rowIndex, p, ds) {
            return record.json.error ? 'grid-row-inactive' : '';
        }
    });

    var columns = new Ext.grid.ColumnModel({
        columns      : [{
            header      : _('domains.label_domain'),
            dataIndex   : 'domain',
            sortable    : true,
            editable    : false,
            width       : 150,
            renderer    : this.renderDomain
        }, {
            header      : _('domains.label_context'),
            dataIndex	: 'context_name',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true
        }, {
            header      : _('domains.label_site_status'),
            dataIndex   : 'site_status',
            sortable    : true,
            editable    : false,
            width       : 100,
            fixed       : true,
            renderer    : this.renderSiteStatus
        }, {
            header      : _('last_modified'),
            dataIndex   : 'editedon',
            sortable    : true,
            editable    : false,
            fixed       : true,
            width       : 200,
            renderer    : this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
        cm          : columns,
        id          : 'domains-grid-domains',
        url         : Domains.config.connector_url,
        baseParams  : {
            action      : 'mgr/domains/getlist'
        },
        fields      : ['id', 'domain', 'base', 'scheme', 'context', 'language', 'site_status', 'page_start', 'page_error', 'primary', 'active', 'editedon', 'page_start_formatted', 'page_error_formatted', 'context_name'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'id',
        plugins     : expander
    });
    
    Domains.grid.Domains.superclass.constructor.call(this, config);
};

Ext.extend(Domains.grid.Domains, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
    
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';
        
        Ext.getCmp('domains-filter-search').reset();
        
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        var menu = [{
            text    : _('domains.domain_update'),
            handler : this.updateDomain
        }, {
            text    : _('domains.domain_duplicate'),
            handler : this.duplicateDomain
        }];
        
        if (1 != parseInt(this.menu.record.primary) || !this.menu.record.primary) {
            menu.push('-', {
                text    : _('domains.domain_remove'),
                handler : this.removeDomain
            });
        }
        
        return menu;
    },
    createDomain: function(btn, e) {
        if (this.createDomainWindow) {
            this.createDomainWindow.destroy();
        }
        
        this.createDomainWindow = MODx.load({
            xtype       : 'domains-window-domain-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
        
        this.createDomainWindow.show(e.target);
    },
    updateDomain: function(btn, e) {
        if (this.updateDomainWindow) {
            this.updateDomainWindow.destroy();
        }
        
        this.updateDomainWindow = MODx.load({
            xtype       : 'domains-window-domain-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'	: {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
        
        this.updateDomainWindow.setValues(this.menu.record);
        this.updateDomainWindow.show(e.target);
    },
    duplicateDomain: function(btn, e) {
        if (this.duplicateDomainWindow) {
            this.duplicateDomainWindow.destroy();
        }
        
        var record = Ext.apply({}, this.menu.record, {
            domain : ''
        });
        
        this.duplicateDomainWindow = MODx.load({
            xtype       : 'domains-window-domain-duplicate',
            record      : record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
        
        this.duplicateDomainWindow.setValues(record);
        this.duplicateDomainWindow.show(e.target);
    },
    removeDomain: function(btn, e) {
        MODx.msg.confirm({
            title       : _('domains.domain_remove'),
            text        : _('domains.domain_remove_confirm'),
            url         : Domains.config.connector_url,
            params      : {
                action      : 'mgr/domains/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    renderDomain: function(d) {
        return String.format('<a href="{0}" target="_blank" title="{1}">{2}</a>', d, d, d);  
    },
    renderSiteStatus: function(d, c) {
        c.css = 1 == parseInt(d) || d ? 'green' : 'red';
        
        return 1 == parseInt(d) || d ? _('domains.online') : _('domains.offline');
    },
    renderBoolean: function(d, c) {
        c.css = 1 == parseInt(d) || d ? 'green' : 'red';
        
        return 1 == parseInt(d) || d ? _('yes') : _('no');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }
        
        return a;
    }
});

Ext.reg('domains-grid-domains', Domains.grid.Domains);

Domains.window.CreateDomain = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        width       : 600,
        autoHeight  : true,
        title       : _('domains.domain_create'),
        url         : Domains.config.connector_url,
        baseParams  : {
            action      : 'mgr/domains/create'
        },
        fields      : [{
            layout      : 'column',
            border      : false,
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .5,
                items       : [{
                    layout      : 'column',
                    border      : false,
                    defaults    : {
                        layout      : 'form',
                        labelSeparator : ''
                    },
                    items       : [{
                        columnWidth : .8,
                        items       : [{
                            xtype       : 'textfield',
                            fieldLabel  : _('domains.label_domain'),
                            description : MODx.expandHelp ? '' : _('domains.label_domain_desc'),
                            name        : 'domain',
                            anchor      : '100%',
                            allowBlank  : false
                        }, {
                            xtype       : MODx.expandHelp ? 'label' : 'hidden',
                            html        : _('domains.label_domain_desc'),
                            cls         : 'desc-under'
                        }]
                    }, {
                        columnWidth : .2,
                        style       : 'margin-right: 0;',
                        items       : [{
                            xtype       : 'checkbox',
                            fieldLabel  : _('domains.label_active'),
                            description : MODx.expandHelp ? '' : _('domains.label_active_desc'),
                            name        : 'active',
                            inputValue  : 1,
                            checked     : true
                        }, {
                            xtype       : MODx.expandHelp ? 'label' : 'hidden',
                            html        : _('domains.label_active_desc'),
                            cls         : 'desc-under'
                        }]
                    }]
                }, {
                    xtype       : 'modx-combo-context',
                    fieldLabel  : _('domains.label_context'),
                    description : MODx.expandHelp ? '' : _('domains.label_context_desc'),
                    name        : 'context',
                    anchor      : '100%',
                    allowBlank  : false,
                    baseParams  : {
                        action      : 'context/getlist',
                        exclude     : 'mgr'
                    }
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_context_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'hidden',
                    name        : 'page_start',
                    id          : 'modx-resource-site-start-create'
                }, {
                    xtype       : 'modx-field-parent-change',
                    fieldLabel  : _('domains.label_page_start'),
                    description : MODx.expandHelp ? '' : _('domains.label_page_start_desc'),
                    anchor      : '100%',
                    name        : 'page_start_formatted',
                    allowBlank  : false,
                    formpanel   : 'domains-panel-home',
                    parentcmp   : 'modx-resource-site-start-create',
                    contextcmp  : null,
                    currentid   : null
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_page_start_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                style       : 'margin-right: 0;',
                items       : [{
                    xtype       : 'modx-combo-language',
                    fieldLabel  : _('domains.label_language'),
                    description : MODx.expandHelp ? '' : _('domains.label_language_desc'),
                    hiddenName  : 'language',
                    anchor      : '100%',
                    allowBlank  : false,
                    value       : MODx.config.manager_language
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_language_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'domains-combo-site-status',
                    fieldLabel  : _('domains.label_site_status'),
                    description : MODx.expandHelp ? '' : _('domains.label_site_status_desc'),
                    name        : 'site_status',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_site_status_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'hidden',
                    name        : 'page_error',
                    id          : 'modx-resource-site-error-create'
                }, {
                    xtype       : 'modx-field-parent-change',
                    fieldLabel  : _('domains.label_page_error'),
                    description : MODx.expandHelp ? '' : _('domains.label_page_error_desc'),
                    anchor      : '100%',
                    name        : 'page_error_formatted',
                    allowBlank  : false,
                    formpanel   : 'domains-panel-home',
                    parentcmp   : 'modx-resource-site-error-create',
                    contextcmp  : null,
                    currentid   : null
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_page_error_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'checkbox',
            hideLabel   : true,
            boxLabel    : _('domains.primary_domain'),
            name        : 'primary',
            inputValue  : 1
        }]
    });
    
    Domains.window.CreateDomain.superclass.constructor.call(this, config);
};

Ext.extend(Domains.window.CreateDomain, MODx.Window);

Ext.reg('domains-window-domain-create', Domains.window.CreateDomain);

Domains.window.UpdateDomain = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        width       : 600,
        autoHeight  : true,
        title       : _('domains.domain_update'),
        url         : Domains.config.connector_url,
        baseParams  : {
            action      : 'mgr/domains/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            layout      : 'column',
            border      : false,
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .5,
                items       : [{
                    layout      : 'column',
                    border      : false,
                    defaults    : {
                        layout      : 'form',
                        labelSeparator : ''
                    },
                    items       : [{
                        columnWidth : .8,
                        items       : [{
                            xtype       : 'textfield',
                            fieldLabel  : _('domains.label_domain'),
                            description : MODx.expandHelp ? '' : _('domains.label_domain_desc'),
                            name        : 'domain',
                            anchor      : '100%',
                            allowBlank  : false
                        }, {
                            xtype       : MODx.expandHelp ? 'label' : 'hidden',
                            html        : _('domains.label_domain_desc'),
                            cls         : 'desc-under'
                        }]
                    }, {
                        columnWidth : .2,
                        style       : 'margin-right: 0;',
                        items       : [{
                            xtype       : 'checkbox',
                            fieldLabel  : _('domains.label_active'),
                            description : MODx.expandHelp ? '' : _('domains.label_active_desc'),
                            name        : 'active',
                            inputValue  : 1
                        }, {
                            xtype       : MODx.expandHelp ? 'label' : 'hidden',
                            html        : _('domains.label_active_desc'),
                            cls         : 'desc-under'
                        }]
                    }]
                }, {
                    xtype       : 'modx-combo-context',
                    fieldLabel  : _('domains.label_context'),
                    description : MODx.expandHelp ? '' : _('domains.label_context_desc'),
                    name        : 'context',
                    anchor      : '100%',
                    allowBlank  : false,
                    baseParams  : {
                        action      : 'context/getlist',
                        exclude     : 'mgr'
                    }
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_context_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'hidden',
                    name        : 'page_start',
                    id          : 'modx-resource-site-start-update'
                }, {
                    xtype       : 'modx-field-parent-change',
                    fieldLabel  : _('domains.label_page_start'),
                    description : MODx.expandHelp ? '' : _('domains.label_page_start_desc'),
                    anchor      : '100%',
                    name        : 'page_start_formatted',
                    allowBlank  : false,
                    formpanel   : 'domains-panel-home',
                    parentcmp   : 'modx-resource-site-start-update',
                    contextcmp  : null,
                    currentid   : null
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_page_start_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                style       : 'margin-right: 0;',
                items       : [{
                    xtype       : 'modx-combo-language',
                    fieldLabel  : _('domains.label_language'),
                    description : MODx.expandHelp ? '' : _('domains.label_language_desc'),
                    name        : 'language',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_language_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'domains-combo-site-status',
                    fieldLabel  : _('domains.label_site_status'),
                    description : MODx.expandHelp ? '' : _('domains.label_site_status_desc'),
                    name        : 'site_status',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_site_status_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'hidden',
                    name        : 'page_error',
                    id          : 'modx-resource-site-error-update'
                }, {
                    xtype       : 'modx-field-parent-change',
                    fieldLabel  : _('domains.label_page_error'),
                    description : MODx.expandHelp ? '' : _('domains.label_page_error_desc'),
                    anchor      : '100%',
                    name        : 'page_error_formatted',
                    allowBlank  : false,
                    formpanel   : 'domains-panel-home',
                    parentcmp   : 'modx-resource-site-error-update',
                    contextcmp  : null,
                    currentid   : null
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('domains.label_page_error_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'checkbox',
            hideLabel   : true,
            boxLabel    : _('domains.primary_domain'),
            name        : 'primary',
            inputValue  : 1
        }]
    });
    
    Domains.window.UpdateDomain.superclass.constructor.call(this, config);
};

Ext.extend(Domains.window.UpdateDomain, MODx.Window);

Ext.reg('domains-window-domain-update', Domains.window.UpdateDomain);

Domains.window.DuplicateDomain = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('domains.domain_duplicate'),
        url         : Domains.config.connector_url,
        baseParams  : {
            action  : 'mgr/domains/duplicate'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('domains.label_domain'),
            description : MODx.expandHelp ? '' : _('domains.label_domain_desc'),
            name        : 'domain',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('domains.label_domain_desc'),
            cls         : 'desc-under'
        }]
    });
    
    Domains.window.DuplicateDomain.superclass.constructor.call(this, config);
};

Ext.extend(Domains.window.DuplicateDomain, MODx.Window);

Ext.reg('domains-window-domain-duplicate', Domains.window.DuplicateDomain);

Domains.combo.SiteStatus = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store   : new Ext.data.ArrayStore({
            mode    : 'local',
            fields  : ['type','label'],
            data    : [
                [1, _('domains.online')],
                [0, _('domains.offline')]
            ]
        }),
        remoteSort  : ['label', 'asc'],
        hiddenName  : 'site_status',
        valueField  : 'type',
        displayField: 'label',
        mode        : 'local'
    });
    
    Domains.combo.SiteStatus.superclass.constructor.call(this,config);
};

Ext.extend(Domains.combo.SiteStatus, MODx.combo.ComboBox);

Ext.reg('domains-combo-site-status', Domains.combo.SiteStatus);