
var tpArbolCuenta, comboParent;

if (window.idcomboiframe) comboParent = window.parent.Ext.getCmp(window.idcomboiframe);

// function CargarInterfaz
function cargarInterfaz(){
	tpArbolCuenta = new Ext.tree.TreePanel({
						  	autoScroll:true,
						  	margins:'2 2 2 2',                    
	        				layoutConfig:{animate:true},
							enableDD:false,
							border:false,
							root:new  Ext.tree.AsyncTreeNode({
				       					text: 'Estructuras',
				        				draggable:false,
				        				id:'Estructuras'
													}),							
							loader: new  Ext.tree.TreeLoader({dataUrl:'buscarhijos'})
		});
/***ViewPort***/
	var vpArbolCuenta = new Ext.Viewport({
						layout:'fit',
						items:tpArbolCuenta
						});
if (comboParent) Ext.UCID.integraInterfaz(tpArbolCuenta,comboParent);
}// Endof cargarInterfaz

cargarInterfaz();