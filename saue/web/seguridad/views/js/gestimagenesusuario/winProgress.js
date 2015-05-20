Normalizar.winProgress = Ext.extend (Normalizar.UI.winProgress, {
	getPB : function () {
		return this.pb	
	},
	update : function () {
		this.rest --;
		d = this.cant - this.rest
		v = d / this.cant;

		if (v > 1)
			v = 1;
			
		//this.pb.updateProgress (v, Math.floor (v * 100) + '%')
	},
	initComponent : function () {
		Normalizar.winProgress.superclass.initComponent.call (this)
	}
})