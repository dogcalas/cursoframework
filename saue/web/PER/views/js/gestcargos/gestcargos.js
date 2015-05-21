var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestcargos', function(){cargarInterfaz();});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

function cargarInterfaz(){


var panel = new Ext.Panel({
		title:'gestcargos',
			id:'pepe',
			renderTo:'panel'
	});




var vpGestSistema = new Ext.Viewport({
			layout:'fit',
			items:panel
		});


}