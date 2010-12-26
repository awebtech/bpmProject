/**
 *  ArchivedObjectsManager
 *
 */
og.ArchivedObjects = function() {

	var actions, moreActions;

	this.doNotRemove = true;
	this.needRefresh = false;

	if (!og.ArchivedObjects.store) {
		og.ArchivedObjects.store = new Ext.data.Store({
			proxy: new og.GooProxy({
				url: og.getUrl('object', 'list_objects', {archived: "true"})
			}),
			reader: new Ext.data.JsonReader({
				root: 'objects',
				totalProperty: 'totalCount',
				id: 'id',
				fields: [
					'name', 'object_id', 'type', 'tags', 
					'createdBy', 'createdById', 'dateCreated',
					'updatedBy', 'updatedById',	'dateUpdated',
					'archivedBy', 'archivedById', 'dateArchived',
					'icon', 'wsIds', 'manager', 'mimeType', 'url', 'ix'
				]
			}),
			remoteSort: true,
			listeners: {
				'load': function() {
					var d = this.reader.jsonData;
					var ws = og.clean(Ext.getCmp('workspace-panel').getActiveWorkspace());
					var tag = og.clean(Ext.getCmp('tag-panel').getSelectedTag());
					if (d.totalCount == 0) {
						if (tag) {
							this.fireEvent('messageToShow', lang("no archived objects with tag message", lang("objects"), ws, tag));
						} else if (d.objects.length == 0) {
							this.fireEvent('messageToShow', lang("no more objects message", lang("objects")));
						} else {
							this.fireEvent('messageToShow', lang("no archived objects message", lang("objects"), ws));
						}
					} else {
						this.fireEvent('messageToShow', "");
					}
					og.showWsPaths();
					var cmp = Ext.getCmp('archivedobjects-manager');
					if (cmp) cmp.getView().focusRow(og.lastSelectedRow.archived+1);
				}
			}
		});
		og.ArchivedObjects.store.setDefaultSort('dateArchived', 'desc');
	}
	this.store = og.ArchivedObjects.store;
	this.store.addListener({messageToShow: {fn: this.showMessage, scope: this}});

	function renderDragHandle(value, p, r) {
		return '<div class="img-grid-drag" title="' + lang('click to drag') + '" onmousedown="Ext.getCmp(\'archivedobjects-manager\').getSelectionModel().selectRow('+r.data.ix+', true);"></div>';
	}
	
	function renderName(value, p, r) {
		var projectsString = String.format('<span class="project-replace">{0}</span>&nbsp;', r.data.wsIds);

		var viewUrl = r.data.url;
		
		var actions = '';
		var actionStyle= ' style="font-size:90%;color:#777777;padding-top:3px;padding-left:18px;background-repeat:no-repeat" ';
		if (r.data.type == 'webpage') {
			viewUrl = og.getUrl('webpage', 'view', {id:r.data.object_id});
			actions += String.format('<a class="list-action ico-open-link" href="{0}" target="_blank" title="{1}" ' + actionStyle + '> </a>',
					r.data.url.replace(/\"/g, escape("\"")).replace(/\'/g, escape("'")), lang('open link in new window', og.clean(value)));
		}
		actions = '<span>' + actions + '</span>';
	
		var name = String.format('<a href="{1}" onclick="og.openLink(\'{1}\');return false;">{0}</a>', og.clean(value), viewUrl);
		
		return projectsString + name + actions;
	}

	function renderType(value, p, r){
		return String.format('<i>' + lang(value) + '</i>')
	}
	
	function renderIcon(value, p, r) {
		var classes = "db-ico ico-unknown ico-" + r.data.type;
		if (r.data.mimeType) {
			var path = r.data.mimeType.replace(/\//ig, "-").split("-");
			var acc = "";
			for (var i=0; i < path.length; i++) {
				acc += path[i];
				classes += " ico-" + acc;
				acc += "-";
			}
		}
		return String.format('<div class="{0}" title="{1}"/>', classes, lang(r.data.type));
	}

	function renderUser(value, p, r) {
		if (r.data.updatedById) {
			return String.format('<a href="{1}" onclick="og.openLink(\'{1}\');return false;">{0}</a>', og.clean(value), og.getUrl('user', 'card', {id: r.data.updatedById}));
		} else {
			return lang("n/a");
		}
	}

	function renderAuthor(value, p, r) {
		if (r.data.createdById) {
			return String.format('<a href="{1}" onclick="og.openLink(\'{1}\');return false;">{0}</a>', og.clean(value), og.getUrl('user', 'card', {id: r.data.createdById}));
		} else {
			return lang("n/a");
		}
	}
	
	function renderArchivedBy(value, p, r) {
		if (r.data.archivedById) {
			return String.format('<a href="{1}" onclick="og.openLink(\'{1}\');return false;">{0}</a>', og.clean(value), og.getUrl('user', 'card', {id: r.data.archivedById}));
		} else {
			return lang("n/a");
		}
	}
	
	function renderDate(value, p, r) {
		if (!value) {
			return "";
		}
		return value;
	}

	function getSelectedIds() {
		var selections = sm.getSelections();
		if (selections.length <= 0) {
			return '';
		} else {
			var ret = '';
			for (var i=0; i < selections.length; i++) {
				ret += "," + selections[i].data.manager + ":" + selections[i].data.object_id;
			}
			og.lastSelectedRow.archived = selections[selections.length-1].data.ix;
			return ret.substring(1);
		}
	}
	this.getSelectedIds = getSelectedIds;
	
	function getFirstSelectedId() {
		if (sm.hasSelection()) {
			return sm.getSelected().data.object_id;
		}
		return '';
	}

	var sm = new Ext.grid.CheckboxSelectionModel();
	sm.on('selectionchange',
		function() {
			if (sm.getCount() <= 0) {
				actions.unarchive.setDisabled(true);
				actions.del.setDisabled(true);
			} else {
				actions.unarchive.setDisabled(false);
				actions.del.setDisabled(true);
				var selections = sm.getSelections();
				for (var i=0; i < selections.length; i++) {
					if (selections[i].data.type != 'project') {
						actions.del.setDisabled(false);
						break;
					}
				}
			}
		});
	var cm = new Ext.grid.ColumnModel([
		sm,{
			id: 'draghandle',
			header: '&nbsp;',
			width: 18,
        	renderer: renderDragHandle,
        	fixed:true,
        	resizable: false,
        	hideable:false,
        	menuDisabled: true
		},{
        	id: 'icon',
        	header: '&nbsp;',
        	dataIndex: 'icon',
        	width: 28,
        	renderer: renderIcon,
        	fixed:true,
        	resizable: false,
        	hideable:false,
        	menuDisabled: true
        },{
			id: 'type',
			header: lang('type'),
			dataIndex: 'type',
			width: 80,
        	renderer: renderType,
        	fixed:false,
        	resizable: true,
        	hideable:true,
        	menuDisabled: true
		},{
			id: 'name',
			header: lang("name"),
			dataIndex: 'name',
			width: 300,
			renderer: renderName,
			sortable: true
        },{
        	id: 'user',
        	header: lang('user'),
        	dataIndex: 'updatedBy',
        	width: 120,
        	renderer: renderUser,
        	hidden: true
        },{
			id: 'tags',
			header: lang("tags"),
			dataIndex: 'tags',
			width: 120,
			hidden: true
        },{
			id: 'last',
			header: lang("last update"),
			dataIndex: 'dateUpdated',
			width: 80,
			renderer: renderDate,
			hidden: true
        },{
			id: 'created',
			header: lang("created on"),
			dataIndex: 'dateCreated',
			width: 80,
			hidden: true,
			renderer: renderDate
		},{
			id: 'author',
			header: lang("author"),
			dataIndex: 'createdBy',
			width: 120,
			renderer: renderAuthor,
			hidden: true
		},{
			id: 'archived',
			header: lang("archived on"),
			dataIndex: 'dateArchived',
			width: 80,
			renderer: renderDate,
			sortable: true
		},{
			id: 'archivedBy',
			header: lang("archived by"),
			dataIndex: 'archivedBy',
			width: 120,
			renderer: renderArchivedBy
		}]);
	cm.defaultSortable = false;

	actions = {
		unarchive: new Ext.Action({
			text: lang('unarchive'),
            tooltip: lang('unarchive selected objects'),
            iconCls: 'ico-unarchive-obj',
			disabled: true,
			handler: function() {
				if (confirm(lang("confirm unarchive selected objects"))) {
					this.load({
						action: 'unarchive',
						objects: getSelectedIds()
					});
					this.getSelectionModel().clearSelections();
				}
			},
			scope: this
		}),
		del: new Ext.Action({
			text: lang('move to trash'),
            tooltip: lang('move selected objects to trash'),
            iconCls: 'ico-trash',
			disabled: true,
			handler: function() {
				if (confirm(lang('confirm move to trash'))) {
					this.load({
						action: 'delete',
						objects: getSelectedIds()
					});
					this.getSelectionModel().clearSelections();
				}
			},
			scope: this
		})
    };
    
	og.ArchivedObjects.superclass.constructor.call(this, {
		enableDrag: true,
		ddGroup : 'WorkspaceDD',
		store: this.store,
		layout: 'fit',
		autoExpandColumn: 'name',
		cm: cm,
		stripeRows: true,
		closable: true,
		id: 'archivedobjects-manager',
		bbar: new og.CurrentPagingToolbar({
			pageSize: og.config['files_per_page'],
			store: this.store,
			displayInfo: true,
			displayMsg: lang('displaying objects of'),
			emptyMsg: lang("no objects to display")
		}),
		viewConfig: {
			forceFit: true
		},
		sm: sm,
		tbar:[
			actions.unarchive,
			'-',
			actions.del
		],
		listeners: {
			'render': {
				fn: function() {
					this.innerMessage = document.createElement('div');
					this.innerMessage.className = 'inner-message';
					var msg = this.innerMessage;
					var elem = Ext.get(this.getEl());
					var scroller = elem.select('.x-grid3-scroller');
					scroller.each(function() {
						this.dom.appendChild(msg);
					});
				},
				scope: this
			}
		}
	});

	var tagevid = og.eventManager.addListener("tag changed", function(tag) {
		if (!this.ownerCt) {
			og.eventManager.removeListener(tagevid);
			return;
		}
		if (this.ownerCt.active) {
			this.load({start:0});
		} else {
    		this.needRefresh = true;
    	}
	}, this);
};

Ext.extend(og.ArchivedObjects, Ext.grid.GridPanel, {
	load: function(params) {
		if (!params) params = {};
		if (typeof params.start == 'undefined') {
			var start = (this.getBottomToolbar().getPageData().activePage - 1) * og.config['files_per_page'];
		} else {
			var start = 0;
		}
		Ext.apply(this.store.baseParams, {
			tag: Ext.getCmp('tag-panel').getSelectedTag(),
			active_project: Ext.getCmp('workspace-panel').getActiveWorkspace().id
		});
		this.store.load({
			params: Ext.applyIf(params, {
				start: start,
				limit: og.config['files_per_page']
			})
		});
		this.needRefresh = false;
	},
	
	activate: function() {
		if (this.needRefresh) {
			this.load({start: 0});
		}
	},
	
	reset: function() {
		this.load({start:0});
	},
	
	moveObjects: function(ws) {
		var selections = this.getSelectionModel().getSelections();
		for (i=0; i<selections.length; i++) {
			if (selections[i].data.manager == 'Projects') {
				this.getSelectionModel().deselectRow(i);
			}
		}
		if (ws == 0) {
			var amail = false;
			for (i=0; i<selections.length; i++) {
				if (selections[i].data.manager == 'MailContents') {
					amail = true;
					break;
				}
			}
			if (amail) {
				og.confirmMoveToAllWs(this.id, lang('confirm unclassify emails'));
			}
		} else {
			var allItemsAreTasksOrMilestones = true;
			for (i=0; i<selections.length; i++) {
				if (selections[i].data.manager != 'ProjectTasks' && selections[i].data.manager != 'ProjectMilestones') {
					allItemsAreTasksOrMilestones = false;
					break;
				}
			}
			// Tasks and events does not keep ws, only move
			if (allItemsAreTasksOrMilestones) {
				this.moveObjectsToWsOrMantainWs(false, ws);
			} else {
				og.moveToWsOrMantainWs(this.id, ws);
			}
		}
	},
	
	moveObjectsToWsOrMantainWs: function(mantain, ws) {
		var selections = this.getSelectionModel().getSelections();
		var amail = false;
		for (i=0; i<selections.length; i++) {
			if (selections[i].data.manager == 'MailContents') {
				amail = true;
				break;
			}
		}
		if (amail) {
			og.askToClassifyUnclassifiedAttachs(this.id, mantain, ws);
		} else {
			this.load({
				action: 'move',
				objects: this.getSelectedIds(),
				moveTo: ws,
				mantainWs: mantain
			});
		}
	},
	
	moveObjectsClassifyingEmails: function(mantain, ws, classifyatts) {
		this.load({
			action: 'move',
			objects: this.getSelectedIds(),
			moveTo: ws,
			mantainWs: mantain,
			classify_atts: classifyatts
		});
	},
	
	trashObjects: function() {
		if (confirm(lang('confirm move to trash'))) {
			this.load({
				action: 'delete',
				objects: this.getSelectedIds()
			});
			this.getSelectionModel().clearSelections();
		}
	},
	
	tagObjects: function(tag) {
		this.load({
			action: 'tag',
			objects: this.getSelectedIds(),
			tagTag: tag
		});
	},
	
	removeTags: function() {
		this.load({
			action: 'untag',
			objects: this.getSelectedIds()
		});
	},
	
	archiveObjects: function() {
		// do nothing.
	},
	
	
	showMessage: function(text) {
		if (this.innerMessage) {
			this.innerMessage.innerHTML = text;
		}
	}
});

Ext.reg("archivedobjects", og.ArchivedObjects);