if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.fontsize = {
	init: function()
	{
		var fonts = [6, 8, 10, 11, 12, 14, 16, 18, 20, 24, 28, 30];
		var that = this;
		var dropdown = {};

		$.each(fonts, function(i, s)
		{
			dropdown['s' + i] = { title: s + 'px', callback: function() { that.setFontsize(s); } };
		});

		dropdown['remove'] = { title: 'Eliminar tamaño', callback: function() { that.resetFontsize(); } };

		this.buttonAdd('fontsize', 'Tamaño de fuente', false, dropdown);
	},
	setFontsize: function(size)
	{
		this.inlineSetStyle('font-size', size + 'px');
	},
	resetFontsize: function()
	{
		this.inlineRemoveStyle('font-size');
	}
};