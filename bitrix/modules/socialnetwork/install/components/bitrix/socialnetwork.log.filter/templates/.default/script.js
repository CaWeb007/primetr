__logOnDateChange = function(sel)
{
	var bShowFrom=false, bShowTo=false, bShowHellip=false, bShowDays=false;

	if(sel.value == 'interval')
		bShowFrom = bShowTo = bShowHellip = true;
	else if(sel.value == 'before')
		bShowTo = true;
	else if(sel.value == 'after' || sel.value == 'exact')
		bShowFrom = true;
	else if(sel.value == 'days')
		bShowDays = true;

	BX('flt_date_from_span').style.display = (bShowFrom? '':'none');
	BX('flt_date_to_span').style.display = (bShowTo? '':'none');
	BX('flt_date_hellip_span').style.display = (bShowHellip? '':'none');
	BX('flt_date_day_span').style.display = (bShowDays? 'inline-block':'none');
	BX('flt_date_day_text_span').style.display = (bShowDays? 'inline-block':'none');
};

function __logOnReload(log_counter)
{
	if (BX("menu-popup-lenta-sort-popup"))
	{
		var arMenuItems = BX.findChildren(BX("menu-popup-lenta-sort-popup"), { className: 'lenta-sort-item' }, true);

		if (!BX.hasClass(arMenuItems[0], 'lenta-sort-item-selected'))
		{
			for (var i = 0; i < arMenuItems.length; i++)
			{
				if (i == 0)
					BX.addClass(arMenuItems[i], 'lenta-sort-item-selected');
				else if (i != (arMenuItems.length-1))
					BX.removeClass(arMenuItems[i], 'lenta-sort-item-selected');
			}
		}
	}

	if (BX("lenta-sort-button"))
	{
		var menuButtonText = BX.findChild(BX("lenta-sort-button"), { className: 'lenta-sort-button-text-internal' }, true, false);
		if (menuButtonText)
			menuButtonText.innerHTML = BX.message('sonetLFAllMessages');
	}

	var counter_cont = BX("sonet_log_counter_preset", true);
	if (counter_cont)
	{
		if (parseInt(log_counter) > 0)
		{
			counter_cont.style.display = "inline-block";
			counter_cont.innerHTML = log_counter;
		}
		else
		{
			counter_cont.innerHTML = '';
			counter_cont.style.display = "none";
		}
	}
}

BitrixLFFilter = function ()
{
	this.id = null;
	this.filterPopup = false;
	this.currentName = null;

	this.obInputName = {};
	this.obSearchInput = {};

	this.obInputContainerName = {};
	this.obContainerInput = {};

	this.popupMenu = null;
	this.menuItems = [];

	this.actualSearchString = '';
	this.minSearchStringLength = 2;
};

BitrixLFFilter.prototype.initLentaMenu = function(params)
{
	if (typeof params.menuItems != 'undefined')
	{
		this.menuItems = params.menuItems;
	}
};

BitrixLFFilter.prototype.showLentaMenu = function(params)
{
	var
		short = (typeof params.short != 'undefined' && params.short),
		bindElement = (typeof params.bindElement != 'undefined' ? BX(params.bindElement) : null);

	if (!bindElement)
	{
		return false;
	}

	if (
		typeof params.siteTemplateId == 'undefined'
		|| params.siteTemplateId != 'bitrix24'
	)
	{
		BX.addClass(bindElement, "lenta-sort-button-active");
	}

	this.popupMenu = BX.PopupMenu.create("lenta-sort-popup", bindElement, BX.util.array_merge((!short ? BX.util.array_merge(this.menuItems.preset, this.menuItems.filter) : []), this.menuItems.actions), {
		offsetTop: (params.siteTemplateId == 'bitrix24' ? -5 : 2),
		offsetLeft: (params.siteTemplateId == 'bitrix24' ? 17 : 43),
		angle : true,
		events : {
			onPopupClose : function() {
				if (
					typeof params.siteTemplateId != 'undefined'
					|| params.siteTemplateId != 'bitrix24'
				)
				{
					BX.removeClass(bindElement, "lenta-sort-button-active");
				}
			}
		}
	});
	this.popupMenu.show();

	return false;
};

BitrixLFFilter.prototype.initFilter = function(params)
{
	var version = (
		typeof params != 'undefined'
		&& typeof params.version != 'undefined'
			? parseInt(params.version)
			: 0
	);

	var filterId = (
		typeof params != 'undefined'
		&& typeof params.filterId != 'undefined'
			? params.filterId
			: 'LIVEFEED'
	);

	this.id = filterId;

	if (version >= 2)
	{
		if (
			typeof params != 'undefined'
			&& typeof params.minSearchStringLength != 'undefined'
			&& parseInt(params.minSearchStringLength) > 0
		)
		{
			this.minSearchStringLength = parseInt(params.minSearchStringLength);
		}

		var filterInstance = BX.Main.filterManager.getById(filterId);
		if (
			filterInstance
			&& (
				filterInstance.getSearch().getSquares().length > 0
				|| filterInstance.getSearch().getSearchString().length > 0
			)
		)
		{
			var pagetitleContainer = BX.findParent(BX(filterId + '_filter_container'), { className: 'pagetitle-wrap'});
			if (pagetitleContainer)
			{
				BX.addClass(pagetitleContainer, "pagetitle-wrap-filter-opened");
			}
		}

		BX.addCustomEvent("BX.Livefeed:refresh", BX.delegate(function() {
			var filterInstance = BX.Main.filterManager.getById(filterId);
			if (filterInstance)
			{
				filterInstance.getPreset().resetPreset(true);
				filterInstance.getSearch().clearForm();
			}
		}, this));
		BX.addCustomEvent("BX.Main.Filter:beforeApply", BX.delegate(function(eventFilterId, values, ob, filterPromise) {
			if (
				eventFilterId != filterId
				|| (
					this.actualSearchString.length > 0
					&& this.actualSearchString.length < this.minSearchStringLength
				)
			)
			{
				return;
			}

			BX.onCustomEvent(window, 'BX.Livefeed.Filter:beforeApply', [values, filterPromise]);
		}, this));
		BX.addCustomEvent("BX.Main.Filter:apply", BX.delegate(function(eventFilterId, values, ob, filterPromise, filterParams) {
			if (
				eventFilterId != filterId
				|| (
					this.actualSearchString.length > 0
					&& this.actualSearchString.length < this.minSearchStringLength
				)
			)
			{
				return;
			}

			BX.onCustomEvent(window, 'BX.Livefeed.Filter:apply', [values, filterPromise, filterParams]);
		}, this));
		BX.addCustomEvent('BX.Filter.Search:input', BX.delegate(function(eventFilterId, searchString) {
			if (eventFilterId == filterId)
			{
				this.actualSearchString = (typeof searchString != 'undefined' ? BX.util.trim(searchString) : '');

				if (
					this.actualSearchString.length > 0
					&& this.actualSearchString.length >= this.minSearchStringLength
				)
				{
					BX.onCustomEvent(window, 'BX.Livefeed.Filter:searchInput', [ searchString ]);
				}
			}
		}, this));
		BX.addCustomEvent('BX.Main.Filter:blur', BX.delegate(function(filterInstance) {
			if (
				filterInstance.getParam('FILTER_ID') == filterId
				&& filterInstance.getSearch().getSquares().length <= 0
				&& filterInstance.getSearch().getSearchString().length <= 0
			)
			{
				var pagetitleContainer = BX.findParent(BX(filterId + '_filter_container'), { className: 'pagetitle-wrap'});
				if (pagetitleContainer)
				{
					BX.removeClass(pagetitleContainer, "pagetitle-wrap-filter-opened");
				}
			}
		}));

		if (BX(this.id + '_filter_container'))
		{
			var f = BX.delegate(function (event) {
				var pagetitleContainer = BX.findParent(event.target, { className: 'pagetitle-wrap'});
				if (
					pagetitleContainer
					&& !BX.hasClass(pagetitleContainer, "pagetitle-wrap-filter-opened")
				)
				{
					BX.addClass(pagetitleContainer, "pagetitle-wrap-filter-opened");
				}
			}, this);

			BX.bind(BX(this.id + '_search_container'), 'click', f);
		}
	}
	else
	{
		__logOnDateChange(document.forms['log_filter'].flt_date_datesel);
		BX('flt_date_from_span').onclick = function(){
			BX.calendar({node: this, field: BX('flt_date_from'), bTime: false});
		};
		BX('flt_date_to_span').onclick = function(){
			BX.calendar({node: this, field: BX('flt_date_to'), bTime: false});
		};
	}
};

BitrixLFFilter.prototype.initDestination = function(params)
{
	this.obInputName[params.name] = params.inputName;
	this.obSearchInput[params.name] = BX(params.inputName);
	this.obInputContainerName[params.name] = params.inputContainerName;
	this.obContainerInput[params.name] = BX(params.inputContainerName);

	if (
		typeof params.items != 'undefined'
		&& typeof params.items.department != 'undefined'
	)
	{
		if (typeof params.items.extranetRoot != 'undefined')
		{
			for(var key in params.items.extranetRoot)
			{
				if (params.items.extranetRoot.hasOwnProperty(key))
				{
					params.items.department[key] = params.items.extranetRoot[key];
				}
			}
		}

		if (!params.items.departmentRelation)
		{
			params.items.departmentRelation = BX.SocNetLogDestination.buildDepartmentRelation(params.items.department);
		}
	}

	BX.SocNetLogDestination.init({
		name : params.name,
		pathToAjax: (typeof params.pathToAjax != 'undefined' ? params.pathToAjax : false),
		searchInput : this.obSearchInput[params.name],
		extranetUser : !!params.extranetUser,
		departmentSelectDisable : !!params.departmentSelectDisable,
		bindMainPopup : {
			node: params.bindNode,
			offsetTop: '5px',
			offsetLeft: '15px'
		},
		bindSearchPopup : {
			node: params.bindNode,
			offsetTop : '5px',
			offsetLeft: '15px'
		},
		callback : {
			select : BX.proxy(this.onSelectDestination, {
				name: params.name,
				containerInput: BX(params.inputContainerName),
				inputContainerName: params.inputContainerName,
				inputName: params.inputName,
				searchInput: BX(params.inputName),
				resultFieldName: params.resultFieldName
			}),
			unSelect : BX.proxy(this.onUnSelectDestination, {
				name: params.name,
				inputContainerName: params.inputContainerName,
				inputName: params.inputName,
				searchInput: BX(params.inputName)
			})
		},
		items : params.items,
		itemsLast : params.itemsLast,
		itemsSelected : params.itemsSelected,
		itemsSelectedUndeleted: (typeof params.itemsSelectedUndeleted != 'undefined' ? params.itemsSelectedUndeleted : {}),
		isCrmFeed : false,
		useClientDatabase: true,
		destSort: params.destSort,
		allowAddUser: false,
		allowSearchEmailUsers: !params.extranetUser,
		userNameTemplate: params.userNameTemplate
	});
	BX.bind(this.obSearchInput[params.name], 'click', function(e) {
		oLFFilter.currentName = params.name;
		BX.SocNetLogDestination.openDialog(params.name);
		return BX.PreventDefault(e);
	});
	BX.bind(this.obSearchInput[params.name], 'keyup', BX.delegate(BX.SocNetLogDestination.BXfpSearch, {
		formName: params.name,
		inputName: oLFFilter.obInputName[params.name]
	}));
	BX.bind(this.obSearchInput[params.name], 'keydown', BX.delegate(BX.SocNetLogDestination.BXfpSearchBefore, {
		formName: params.name,
		inputName: oLFFilter.obInputName[params.name]
	}));

};

BitrixLFFilter.prototype.clearInput = function()
{
	if (this.obContainerInput[this.currentName])
	{
		var arItems = BX.findChildren(this.obContainerInput[this.currentName], { className: 'feed-add-post-destination' }, false);
		for (var i = 0; i < arItems.length; i++)
		{
			BX.SocNetLogDestination.deleteItem(arItems[i].attributes['data-id'].value, arItems[i].attributes['data-type'].value, this.currentName);
		}
	}
};

BitrixLFFilter.prototype.onSelectDestination = function(item, type, search, bUndeleted)
{
	oLFFilter.clearInput();

	BX.SocNetLogDestination.BXfpSelectCallback({
		formName: this.name,
		item: item,
		type: type,
		search: search,
		bUndeleted: bUndeleted,
		containerInput: this.containerInput,
		valueInput: this.searchInput,
		varName: this.resultFieldName
	});

	this.searchInput.style.display = "none";
	if (
		this.name == 'feed-filter-created-by'
		&& BX("flt_comments_cont")
	)
	{
		BX("flt_comments_cont").style.display = "block";
	}

	BX.SocNetLogDestination.closeDialog();
	BX.SocNetLogDestination.closeSearch();
};

BitrixLFFilter.prototype.onUnSelectDestination = function(item)
{
	var elements = BX.findChildren(BX(this.inputContainerName), {attribute: {'data-id': '' + item.id + ''}}, true);
	if (elements !== null)
	{
		for (var j = 0; j < elements.length; j++)
		{
			BX.remove(elements[j]);
		}
	}
	BX(this.inputName).value = '';

	this.searchInput.style.display = "inline-block";
	if (
		this.name == 'feed-filter-created-by'
		&& BX("flt_comments_cont")
	)
	{
		BX("flt_comments_cont").style.display = "none";
	}
};

BitrixLFFilter.prototype.ShowFilterPopup = function(bindElement)
{
	if (!oLFFilter.filterPopup)
	{
		BX.ajax.get(BX.message('sonetLFAjaxPath'), function(data)
		{
			BX.closeWait(bindElement);

			oLFFilter.filterPopup = new BX.PopupWindow(
				'bx_log_filter_popup',
				bindElement,
				{
					closeIcon : false,
					offsetTop: 5,
					autoHide: true,
					zIndex : -100,
					//angle : { offset : 59},
					className : 'sonet-log-filter-popup-window',
					events : {
						onPopupClose: function() {
							if (!BX.hasClass(this.bindElement, "pagetitle-menu-filter-set"))
								BX.removeClass(this.bindElement, "pagetitle-menu-filter-selected")
						},
						onPopupShow: function() { BX.addClass(this.bindElement, "pagetitle-menu-filter-selected")}
					}
				}
			);

			var filter_block = BX.create('DIV', {html: BX.util.trim(data)});
			oLFFilter.filterPopup.setContent(filter_block.firstChild);
			oLFFilter.filterPopup.show();
		});
	}
	else
	{
		oLFFilter.filterPopup.show();
	}
};

BitrixLFFilter.prototype.__SLFShowExpertModePopup = function(bindObj)
{
	var modalWindow = new BX.PopupWindow('setExpertModePopup', bindObj, {
		closeByEsc: false,
		closeIcon: false,
		autoHide: false,
		overlay: true,
		events: {},
		buttons: [],
		zIndex : 0,
		content: BX.create('DIV', {
			children: [
				BX.create('DIV', {
					props: {
						className: 'bx-slf-popup-title'
					},
					text: BX.message('sonetLFExpertModePopupTitle')
				}),
				BX.create('DIV', {
					props: {
						className: 'bx-slf-popup-content'
					},
					children: [
						BX.create('DIV', {
							props: {
								className: 'bx-slf-popup-cont-title'
							},
							html: BX.message('sonetLFExpertModePopupText1')
						}),
						BX.create('DIV', {
							props: {
								className: 'bx-slf-popup-descript'
							},
							children: [
								BX.create('DIV', {
									html: BX.message('sonetLFExpertModePopupText2')
								}),
								BX.create('IMG', {
									props: {
										className: 'bx-slf-popup-descript-img'
									},
									attrs: {
										src: BX.message('sonetLFExpertModeImagePath'),
										width: 354,
										height: 201
									}
								})
							]
						})
					]
				}),
				BX.create('DIV', {
					props: {
						className: 'popup-window-buttons'
					},
					children: [
						BX.create('SPAN', {
							props: {
								className: 'popup-window-button popup-window-button-accept'
							},
							events: {
								click: function () {
									BX.ajax({
										method: 'POST',
										dataType: 'json',
										url: BX.message('ajaxControllerURL'),
										data: {
											sessid : BX.bitrix_sessid(),
											closePopup: 'Y'
										},
										onsuccess: function(response)
										{
											if (
												typeof (response) != 'undefined'
												&& typeof (response.SUCCESS) != 'undefined'
												&& response.SUCCESS == 'Y'
											)
											{
												modalWindow.close();
												top.location = top.location.href;
											}
										}
									});
								}
							},
							children: [
								BX.create('SPAN', {
									props: {
										className: 'popup-window-button-left'
									}
								}),
								BX.create('SPAN', {
									props: {
										className: 'popup-window-button-text'
									},
									text: BX.message('sonetLFDialogRead')
								}),
								BX.create('SPAN', {
									props: {
										className: 'popup-window-button-right'
									}
								})
							]
						})
					]
				})
			]
		})
	});
	modalWindow.show();
};

BitrixLFFilter.prototype.onClickMenuItem = function(params)
{
	if (typeof params.menuItem != 'undefined')
	{
		BX.toggleClass(params.menuItem, 'lenta-sort-item-selected');
	}
	this.popupMenu.close();
	if (typeof params.href != 'undefined')
	{
		top.location.href = params.href;
	}
};

BitrixLFFilter.prototype.closeHint = function(element)
{

};

BitrixLFFilterDestinationSelectorManager = {
	controls: {},

	onSelect: function(item, type, search, bUndeleted, name, state)
	{
		BX.SocNetLogDestination.obItemsSelected[name] = {};
		BX.SocNetLogDestination.obItemsSelected[name][item.id] = type;

		var control = BitrixLFFilterDestinationSelectorManager.controls[name];
		if(control)
		{
			control.setData(BX.util.htmlspecialcharsback(item.name), item.id);
			control.getLabelNode().value = '';
			control.getLabelNode().blur();

			if (BX.SocNetLogDestination.popupWindow != null)
			{
				BX.SocNetLogDestination.popupWindow.close();
			}
			if (BX.SocNetLogDestination.popupSearchWindow != null)
			{
				BX.SocNetLogDestination.popupSearchWindow.close();
			}
		}
	}
};

BitrixLFFilterDestinationSelector = function ()
{
	this.id = "";
	this.filterId = "";
	this.settings = {};
	this.fieldId = "";
	this.control = null;
	this.inited = null;
};

BitrixLFFilterDestinationSelector.create = function(id, settings)
{
	var self = new BitrixLFFilterDestinationSelector(id, settings);
	self.initialize(id, settings);
	BX.onCustomEvent(window, 'BX.Livefeed.Filter:create', [ id ]);
	return self;
};

BitrixLFFilterDestinationSelector.prototype.getSetting = function(name, defaultval)
{
	return this.settings.hasOwnProperty(name) ? this.settings[name] : defaultval;
};

BitrixLFFilterDestinationSelector.prototype.getSearchInput = function()
{
	return this.control ? this.control.getLabelNode() : null;
};

BitrixLFFilterDestinationSelector.prototype.initialize = function(id, settings)
{
	this.id = id;
	this.settings = settings ? settings : {};
	this.fieldId = this.getSetting("fieldId", "");
	this.filterId = this.getSetting("filterId", "");
	this.inited = false;
	this.opened = null;
	this.closed = null;

	var initialValue = this.getSetting("initialValue",false);
	if (!!initialValue)
	{
		var initialSettings = {};
		initialSettings[this.fieldId] = initialValue.itemId;
		initialSettings[this.fieldId + '_label'] = initialValue.itemName;

		BX.Main.filterManager.getById(this.filterId).getApi().setFields(initialSettings);
	}
	BX.addCustomEvent(window, "BX.Main.Filter:customEntityFocus", BX.delegate(this.onCustomEntitySelectorOpen, this));
	BX.addCustomEvent(window, "BX.Main.Filter:customEntityBlur", BX.delegate(this.onCustomEntitySelectorClose, this));
	BX.addCustomEvent(window, "BX.Main.Filter:onGetStopBlur", BX.delegate(this.onGetStopBlur, this));
	BX.addCustomEvent(window, "BX.Main.Selector:beforeInitDialog", BX.delegate(this.onBeforeInitDialog, this));
	BX.addCustomEvent(window, "BX.SocNetLogDestination:onBeforeSwitchTabFocus", BX.delegate(this.onBeforeSwitchTabFocus, this));
	BX.addCustomEvent(window, "BX.SocNetLogDestination:onBeforeSelectItemFocus", BX.delegate(this.onBeforeSelectItemFocus, this));
	BX.addCustomEvent(window, "BX.Main.Filter:customEntityRemove", BX.delegate(this.onCustomEntityRemove, this));
};

BitrixLFFilterDestinationSelector.prototype.open = function()
{
	var name = this.id;

	if (!this.inited)
	{
		var input = this.getSearchInput();
		input.id = input.name;

		BX.addCustomEvent(window, "BX.Main.Selector:afterInitDialog", BX.delegate(function(params) {
			if (
				typeof params.id != 'undefined'
				|| params.id != this.id
			)
			{
				return;
			}

			this.opened = true;
			this.closed = false;
		}, this));

		BX.onCustomEvent(window, 'BX.Livefeed.Filter:openInit', [ {
			id: this.id,
			inputId: input.id,
			containerId: input.id
		} ]);

		this.inited = true;
	}
	else
	{
		BX.onCustomEvent(window, 'BX.Livefeed.Filter:open', [ {
			id: this.id,
			bindNode: this.control.getField()
		} ]);

		this.opened = true;
		this.closed = false;
	}
};

BitrixLFFilterDestinationSelector.prototype.close = function()
{
	BX.SocNetLogDestination.closeDialog();
	this.opened = false;
	this.closed = true;
};

BitrixLFFilterDestinationSelector.prototype.onCustomEntitySelectorOpen = function(control)
{
	var fieldId = control.getId();

	if(this.fieldId !== fieldId)
	{
		this.control = null;
	}
	else
	{
		this.control = control;

		if(this.control)
		{
			var current = this.control.getCurrentValues();
			this.currentUser = { "entityId": current["value"] };
		}

		BitrixLFFilterDestinationSelectorManager.controls[this.id] = this.control;

		if (!this.opened)
		{
			this.open();
		}
		else
		{
			this.close();
		}
	}
};

BitrixLFFilterDestinationSelector.prototype.onCustomEntitySelectorClose = function(control)
{
	if(
		this.fieldId === control.getId()
		&& this.inited === true
	)
	{
		this.control = null;
		this.close();
	}
};

BitrixLFFilterDestinationSelector.prototype.onGetStopBlur = function(event, result)
{
	if (BX.findParent(event.target, { className: 'bx-lm-box'}))
	{
		result.stopBlur = true;
	}
};

BitrixLFFilterDestinationSelector.prototype.onCustomEntityRemove = function(control)
{
	if(this.fieldId === control.getId())
	{
		if (
			typeof control.hiddenInput != 'undefined'
			&& typeof control.hiddenInput.value != 'undefined'
			&& typeof BX.SocNetLogDestination.obItemsSelected[this.id] != 'undefined'
			&& typeof BX.SocNetLogDestination.obItemsSelected[this.id][control.hiddenInput.value] != 'undefined'
		)
		{
			delete BX.SocNetLogDestination.obItemsSelected[this.id][control.hiddenInput.value];
		}
	}
};

BitrixLFFilterDestinationSelector.prototype.onBeforeSwitchTabFocus = function(ob)
{
	if(this.id === ob.id)
	{
		ob.blockFocus = true;
	}
};

BitrixLFFilterDestinationSelector.prototype.onBeforeSelectItemFocus = function(ob)
{
	if(this.id === ob.id)
	{
		ob.blockFocus = true;
	}
};

BitrixLFFilterDestinationSelector.prototype.onBeforeInitDialog = function(params)
{
	if (
		typeof params.id == 'undefined'
		|| params.id != this.id
	)
	{
		return;
	}

	if (this.closed)
	{
		params.blockInit = true;
	}
};

oLFFilter = new BitrixLFFilter;
window.oLFFilter = oLFFilter;