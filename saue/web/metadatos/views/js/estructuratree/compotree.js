
/**
 * @author proyecto
 */
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
							loader: new  Ext.tree.TreeLoader({dataUrl:'buscarcomposicion'})
		});
/***ViewPort***/
	var vpArbolCuenta = new Ext.Viewport({
						layout:'fit',
						items:tpArbolCuenta
						});
	function AbrirTreeComposicion(nodosel){
				 var root = tpArbolCuenta.getRootNode();
				 var loader = tpArbolCuenta.getLoader();
				 root.setText(nodosel.text);
				 root.id=nodosel.id; 
				 loader.baseParams ={idestructura:nodosel.id};	
				 loader.load(root);
			}
if (comboParent) Ext.UCID.integraInterfaz(tpArbolCuenta,comboParent);
}// Endof cargarInterfaz

cargarInterfaz();