App.modules.linkToObjectForm = {
	pickObject: function(before, config) {
		og.ObjectPicker.show(function (objs) {
			if (objs) {
				for (var i=0; i < objs.length; i++) {
					var obj = objs[i].data;
					App.modules.linkToObjectForm.addObject(this, obj, config);
				}
			}
		}, before, config);
	},

	addObject: function(before, obj, config) {
		if (!config) config = {};
		var parent = before.parentNode;
		var count = parent.getElementsByTagName('span').length;
		var div = document.createElement('div');
		div.className = "og-add-template-object ico-" + obj.type + (count % 2 ? " odd" : "");
		/*div.onmouseover = App.modules.linkToObjectForm.mouseOver;
		div.onmouseout = App.modules.linkToObjectForm.mouseOut;*/
		var name = og.clean(obj.name);;
		if (typeof config.renderName == 'function') {
			name = config.renderName(obj, count);
		}
		div.innerHTML =
			'<input type="hidden" name="linked_objects[' + count + ']" value="' + obj.manager + ":" + obj.object_id + '" />' +
			'<span class="name">' +
			name +
			'</span>' +
			'<a href="#" onclick="App.modules.linkToObjectForm.removeObject(this.parentNode)" class="removeDiv" style="display:block">' + lang('remove') + '</a>';
		parent.insertBefore(div, before);
	},


	removeObject: function(div) {
		var parent = div.parentNode;
		parent.removeChild(div);
		var inputs = parent.getElementsByTagName('input');
		for (var i=0; i < inputs.length; i++) {
			inputs[i].name = 'linked_objects[' + i + ']';
		}
		var d = parent.firstChild;
		var i=0;
		while (d != null) {
			if (d.tagName == 'DIV') {
				Ext.fly(d).removeClass("odd");
				if (i % 2) {
					Ext.fly(d).addClass("odd");
				}
				i++;
			}
			d = d.nextSibling;
		}
	}
};