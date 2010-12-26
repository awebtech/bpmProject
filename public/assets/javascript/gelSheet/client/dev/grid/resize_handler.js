/*  Gelsheet Project, version 0.0.1 (Pre-alpha)
 *  Copyright (c) 2008 - Ignacio Vazquez, Fernando Rodriguez, Juan Pedro del Campo
 *
 *  Ignacio "Pepe" Vazquez <elpepe22@users.sourceforge.net>
 *  Fernando "Palillo" Rodriguez <fernandor@users.sourceforge.net>
 *  Juan Pedro "Perico" del Campo <pericodc@users.sourceforge.net>
 *
 *  Gelsheet is free distributable under the terms of an GPL license.
 *  For details see: http://www.gnu.org/copyleft/gpl.html
 *
 */
function SizeHandler(verticalWay){
	var self = document.createElement("DIV");

	self.construct = function(verticalWay,top,left,width,height){
		//WrapStyle(this);
		this.element = undefined;
		this.style.position = "absolute";
		this.style.top = px(0);
		this.style.left = px(0);
		this.style.width = px(0);
		this.style.height = px(0);

		this.style.backgroundColor = "#CCC";
		if(verticalWay){
			this.style.cursor = "e-resize";
			this.style.width = px(5);
		}
		else{
			this.style.cursor = "s-resize";
			this.style.height = px(5);
			}
		this.style.zIndex = 2000;
		this.resizing = false;
		WrapStyle(this);
	}

	
	if(verticalWay){
		self.startResizing = function(){
			this.offset = parseInt(this.style.left);
			this.style.height = "100%";
		}

		self.endResizing = function(){
			this.style.height = "0px";
			return this.offset - parseInt(this.style.left);
		}

		self.onmousedown = function(e){
			this.resizing = true;
			this.style.height = "100%";
			this.style.backgroundColor = "#CCC";
			var pos = (window.Event) ? parseInt(e.pageX): parseInt(event.clientX);
			self.offset = parseInt(this.style.left)-pos;
//			EventManager.register('mousemove',this.mousemoveCBK);
//			EventManager.register('mouseup',this.mouseupCBK);
		}
	}else{ //Horizontal
		self.startResizing = function(pos){
			this.offset = parseInt(pos);
			this.style.width = "100%";
		}

		self.endResizing = function(pos){
			this.style.width = "0px";
			return (this.offset - parseInt(pos) +59 );
		}
	
	}

	self.construct(verticalWay);
	return self;
}
