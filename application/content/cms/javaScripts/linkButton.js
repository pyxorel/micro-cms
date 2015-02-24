(function($) {
	elRTE.prototype.ui.prototype.buttons.linkPage = function(rte, name) {
		this.constructor.prototype.constructor.call(this, rte, name);
		
		this.command = function() {
			this.rte.history.add();
			this.rte.selection.insertHtml('<div class="linkPage" />', true);
			this.rte.window.focus();
			this.rte.ui.update();
		};
		
		this.update = function() {
			this.domElem.removeClass('disabled');
		};
	};
})(jQuery);