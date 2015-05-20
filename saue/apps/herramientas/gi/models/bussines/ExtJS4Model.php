<?php
class ExtJS4Model extends ZendExt_Model
{
    public function ExtJS4Model()
    {
        parent::ZendExt_Model();
    }

    public function generarInterfaz($concepto)
    {
        $colft = "";
        $con = new SimpleXMLElement($_SERVER['DOCUMENT_ROOT'] . "/../apps/herramientas/gi/comun/recursos/xml/conceptos/" . $concepto . ".xml", NULL, TRUE);
        foreach ($con->concepto->atributos->atributo as $atributo) {
            if ($col == "") {
                $col = "{\n  name: '" . (String)$atributo->denominacion . "'\n}";
                $cold = "{\n  text: '" . (String)$atributo->denominacion . "',\n  flex: 1,\n  dataIndex: '" . (String)$atributo->denominacion . "'\n}";
                switch ((String)$atributo->componente) {
                    case 'Arbol':
                        $codigo .= "var stTrForm" . (String)$atributo->denominacion . " = Ext.create('Ext.data.TreeStore', {\n proxy: {\n type: 'ajax',\n url:'cargar" . (String)$con->concepto->denominacion . "' \n  },\n root: {\n  id: 'src',\n expanded: true\n},\n folderSort: true,\n sorters: [{\n property: 'text',\n direction: 'ASC'\n}]\n });\n var TrForm" . (String)$atributo->denominacion . " = Ext.create('Ext.tree.Panel', {\n  title:'Arbol',\n  store: stTrForm" . (String)$atributo->denominacion . ",\n  collapsible: true,\n  viewConfig: {\n plugins: {\n ptype: 'treeviewdragdrop'\n  }\n  },\n width: '37%',\n  region: 'center',\n   useArrows: true,\n dockedItems: [{\n  xtype: 'toolbar',\n items: [{\n text: 'Expandir todo',\n handler: function(){\n TrForm" . (String)$atributo->denominacion . ".expandAll();\n  }\n }, {\n  text: 'Contraer todo',\n  handler: function(){\n TrForm" . (String)$atributo->denominacion . ".collapseAll();\n  }\n }]\n }]\n});\n stTrForm" . (String)$atributo->denominacion . ".load();\n";
                        $colft = "TrForm" . (String)$atributo->denominacion;
                        break;
                    case 'Checkbox':
                        $colf = " {xtype: 'fieldcontainer',\n  defaultType: 'checkboxfield',\n	items: [{\n		boxLabel  : '" . (String)$atributo->denominacion . "'\n}]}";
                        break;
                    case 'Combobox':
                        $codigo .= "var stcmb" . (String)$atributo->denominacion . "=Ext.create('Ext.data.Store', {\n  fields:[],\n   data : []\n });\n  var cmb" . (String)$atributo->denominacion . " = Ext.create('Ext.form.field.ComboBox', {\n fieldLabel: 'Seleccione',\n editable: false,\n width: 500,\n  labelWidth: 130,\n store: stcmb" . (String)$atributo->denominacion . ",\n emptyText:'--seleccione--',\n queryMode: 'local',\n typeAhead: true\n});\n";
                        $colf = "cmb" . (String)$atributo->denominacion;
                        break;
                    case 'Datefield':
                        $colf = "{xtype: 'datefield',\n anchor: '100%',\n fieldLabel: '" . (String)$atributo->denominacion . "',\n  }";
                        break;
                    case 'Radio':
                        $colf = "{xtype: 'fieldcontainer',\n   defaultType: 'radiofield',\n   defaults: {\n   flex: 1\n   },\n   layout: 'hbox',\n  items: [{  boxLabel  : '" . (String)$atributo->denominacion . "',\n}]}";
                        break;
                    case 'TextArea':
                        $colf = "{xtype: 'textareafield',\n  grow : true,\n  fieldLabel: '" . (String)$atributo->denominacion . "',\n  anchor    : '100%'\n}";
                        break;
                    case 'Textfield':
                        $colf = "{xtype:'textfield',\n  fieldLabel:'" . (String)$atributo->denominacion . "',\n  name:'" . (String)$atributo->denominacion . "',\n anchor: '100%'\n}";
                        break;
                }

            } else {
                $col .= ",{\n  name: '" . (String)$atributo->denominacion . "'\n}";
                $cold .= ",{\n  text: '" . (String)$atributo->denominacion . "',\n  flex: 1,\n  dataIndex: '" . (String)$atributo->denominacion . "'\n}";
                switch ((String)$atributo->componente) {
                    case 'Arbol':
                        $codigo .= "var stTrForm" . (String)$atributo->denominacion . " = Ext.create('Ext.data.TreeStore', {\n proxy: {\n type: 'ajax',\n url:'cargar" . (String)$con->concepto->denominacion . "' \n  },\n root: {\n  id: 'src',\n expanded: true\n},\n folderSort: true,\n sorters: [{\n property: 'text',\n direction: 'ASC'\n}]\n });\n var TrForm" . (String)$atributo->denominacion . " = Ext.create('Ext.tree.Panel', {\n  title:'Arbol',\n  store: stTrForm" . (String)$atributo->denominacion . ",\n  collapsible: true,\n  viewConfig: {\n plugins: {\n ptype: 'treeviewdragdrop'\n  }\n  },\n  width: '37%',\n  region: 'center',\n   useArrows: true,\n dockedItems: [{\n  xtype: 'toolbar',\n items: [{\n text: 'Expandir todo',\n handler: function(){\n TrForm" . (String)$atributo->denominacion . ".expandAll();\n  }\n }, {\n  text: 'Contraer todo',\n  handler: function(){\n TrForm" . (String)$atributo->denominacion . ".collapseAll();\n  }\n }]\n }]\n});\n stTrForm" . (String)$atributo->denominacion . ".load();\n";
                        $colft .= ",TrForm" . (String)$atributo->denominacion;
                        break;
                    case 'Checkbox':
                        $colf .= ", {xtype: 'fieldcontainer',\n  defaultType: 'checkboxfield',\n	items: [{\n		boxLabel  : '" . (String)$atributo->denominacion . "'\n}]}";
                        break;
                    case 'Combobox':
                        $codigo .= "var stcmb" . (String)$atributo->denominacion . "=Ext.create('Ext.data.Store', {\n  fields:[],\n   data : []\n });\n  var cmb" . (String)$atributo->denominacion . " = Ext.create('Ext.form.field.ComboBox', {\n fieldLabel: 'Seleccione',\n editable: false,\n width: 500,\n  labelWidth: 130,\n store: stcmb" . (String)$atributo->denominacion . ",\n emptyText:'--seleccione--',\n queryMode: 'local',\n typeAhead: true\n});\n";
                        $colf .= ",cmb" . (String)$atributo->denominacion;
                        break;
                    case 'Datefield':
                        $colf .= ",{xtype: 'datefield',\n anchor: '100%',\n fieldLabel: '" . (String)$atributo->denominacion . "',\n  }";
                        break;
                    case 'Radio':
                        $colf .= ",{xtype: 'fieldcontainer',\n   defaultType: 'radiofield',\n   defaults: {\n   flex: 1\n   },\n   layout: 'hbox',\n  items: [{  boxLabel  : '" . (String)$atributo->denominacion . "',\n}]}";
                        break;
                    case 'TextArea':
                        $colf .= ",{xtype: 'textareafield',\n  grow : true,\n  fieldLabel: '" . (String)$atributo->denominacion . "',\n  anchor: '100%'\n}";
                        break;
                    case 'Textfield':
                        $colf .= ",{\n  xtype:'textfield',\n  fieldLabel:'" . (String)$atributo->denominacion . "',\n  name:'" . (String)$atributo->denominacion . "',\n anchor: '100%'\n}";
                        break;
                }
            }
        }
        switch ((String)$con->concepto->plantilla) {
            case 'CRUD-Simple':
                $urlG = (String)$con->concepto->urlG;
                break;
            case 'Arbol y Grid':
                $urlG = (String)$con->concepto->urlG;
                $urlA = (String)$con->concepto->urlA;
                break;
        }
        $add = "var btnAdicionar" . (String)$con->concepto->denominacion . " = Ext.create('Ext.Button', {\n  id: 'btnAgr" . (String)$con->concepto->denominacion . "',\n  text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',\n  icon: perfil.dirImg + 'adicionar.png',\n  iconCls:'btn',\n  handler: function() {	\n   mostForm" . (String)$con->concepto->denominacion . "('add');\n }\n});\n";
        $mod = "var btnModificar" . (String)$con->concepto->denominacion . " = Ext.create('Ext.Button', {\n  id: 'btnMod" . (String)$con->concepto->denominacion . "',\n  text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',\n  disabled: true,\n   icon: perfil.dirImg + 'modificar.png',\n  iconCls:'btn',\n  handler: function() {	\n   mostForm" . (String)$con->concepto->denominacion . "('mod');\n}\n});\n";
        $del = "var btnEliminar" . (String)$con->concepto->denominacion . " = Ext.create('Ext.Button', {\n  id: 'btnEli" . (String)$con->concepto->denominacion . "',\n  text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',\n  disabled: true,\n   icon: perfil.dirImg + 'eliminar.png',\n  iconCls:'btn',\n  handler: function() {\n   Ext.MessageBox.show({\n   title:'Eliminar',\n   msg: 'Desea eliminar el elemento?',\n   buttons: Ext.MessageBox.YESNO,\n   icon: Ext.MessageBox.QUESTION,\n  }); \n}\n});\n";
        $grid = "var stGp" . (String)$con->concepto->denominacion . " = Ext.create('Ext.data.ArrayStore', {\n fields: [" . $col . "],\n proxy: {\n  type: 'ajax',\n  url:'" . $urlG . "',\n  actionMethods: {\n  read: 'POST'\n }\n  }\n});\n var Gp" . (String)$con->concepto->denominacion . " =Ext.create('Ext.grid.Panel', {\n store: stGp" . (String)$con->concepto->denominacion . ",\n stateful: true,\n stateId:'stateGrid',\n columns: [" . $cold . "],\n region: 'center',\n tbar:[btnAdicionar" . (String)$con->concepto->denominacion . ",btnModificar" . (String)$con->concepto->denominacion . ",btnEliminar" . (String)$con->concepto->denominacion . "]\n});\n stGp" . (String)$con->concepto->denominacion . ".load();\n";
        $arbol = "var stTr" . (String)$con->concepto->denominacion . " = Ext.create('Ext.data.TreeStore', {\n proxy: {\n type: 'ajax',\n url:'" . $urlA . "' \n  },\n root: {\n  id: 'src',\n expanded: true\n},\n folderSort: true,\n sorters: [{\n property: 'text',\n direction: 'ASC'\n}]\n });\n var Tr" . (String)$con->concepto->denominacion . " = Ext.create('Ext.tree.Panel', {\n  title:'Arbol',\n  store: stTr" . (String)$con->concepto->denominacion . ",\n  collapsible: true,\n  viewConfig: {\n plugins: {\n ptype: 'treeviewdragdrop'\n  }\n  },\n height: 700,\n width: 250,\n  region: 'west',\n   useArrows: true,\n dockedItems: [{\n  xtype: 'toolbar',\n items: [{\n text: 'Expandir todo',\n handler: function(){\n  Tr" . (String)$con->concepto->denominacion . ".expandAll();\n  }\n }, {\n  text: 'Contraer todo',\n  handler: function(){\n Tr" . (String)$con->concepto->denominacion . ".collapseAll();\n  }\n }]\n }]\n});\n stTr" . (String)$con->concepto->denominacion . ".load();\n";
        $ucid = "UCID.portal.cargarAcciones(window.parent.idFuncionalidad);\n";
        if ($colft == "") {
            $fun = "var winAdicionar" . (String)$con->concepto->denominacion . ";\n var winModificar" . (String)$con->concepto->denominacion . ";\n var form" . (String)$con->concepto->denominacion . "=Ext.create('Ext.form.Panel', {\n   frame:true,\n  bodyStyle:'padding:5px 5px 0',\n  width: 350,\n  fieldDefaults: {\n   msgTarget: 'side',\n   labelWidth: 75\n  },\n  defaults: {\n   anchor: '100%'\n  },\n  items: [" . $colf . "]\n });\n function mostForm" . (String)$con->concepto->denominacion . "(opcion){\n  switch(opcion){\n  case 'add':\n  {\n   winAdicionar" . (String)$con->concepto->denominacion . " = Ext.create('Ext.Window', {\n   title: 'Adicionar',\n   closeAction: 'hide',\n   width: 500,\n    height: 250,\n    x: 220,\n    y: 100,\n    constrain: true,\n    layout: 'fit',\n   buttons: [{\n    text: 'Aplicar',\n    icon:perfil.dirImg + 'aplicar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Aceptar',\n    icon:perfil.dirImg + 'aceptar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Cancelar',\n    icon:perfil.dirImg + 'cancelar.png',\n    handler:function(){\n    winAdicionar" . (String)$con->concepto->denominacion . ".hide();\n    }\n   }\n   ]\n    });\n   winAdicionar" . (String)$con->concepto->denominacion . ".add(form" . (String)$con->concepto->denominacion . ");\n   winAdicionar" . (String)$con->concepto->denominacion . ".show();\n  }break;\n  case 'mod':\n  {\n   winModificar" . (String)$con->concepto->denominacion . " = Ext.create('Ext.Window', {\n   title: 'Modificar',\n   closeAction: 'hide',\n   width: 500,\n    height: 250,\n    x: 220,\n    y: 100,\n    constrain: true,\n    layout: 'fit',\n   buttons: [{\n    text: 'Aplicar',\n    icon:perfil.dirImg + 'aplicar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Aceptar',\n    icon:perfil.dirImg + 'aceptar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Cancelar',\n    icon:perfil.dirImg + 'cancelar.png',\n    handler:function(){\n    winModificar" . (String)$con->concepto->denominacion . ".hide();\n    }\n   }\n   ]\n    });\n   winModificar" . (String)$con->concepto->denominacion . ".add(form" . (String)$con->concepto->denominacion . ");\n   winModificar" . (String)$con->concepto->denominacion . ".show();\n  }break;\n}\n}\n";
        } else {
            $fun = "var winAdicionar" . (String)$con->concepto->denominacion . ";\n var winModificar" . (String)$con->concepto->denominacion . ";\n var form1" . (String)$con->concepto->denominacion . "=Ext.create('Ext.form.Panel', {\n   labelAlign: 'top',\n  frame:true,\n  region: 'west',\n   width: 200,\n  bodyStyle:'padding:5px 5px 0',\n   items: [{\n columnWidth: 1,\n layout: 'form',\n  margin: '5 5 5 5',\n border: false,\n  items: [\n" . $colf . "\n]}]\n });\n var form" . (String)$con->concepto->denominacion . "=Ext.create('Ext.form.Panel', {\n   layout: 'border',\n  items: [form1" . (String)$con->concepto->denominacion . "," . $colft . "]\n });\n function mostForm" . (String)$con->concepto->denominacion . "(opcion){\n  switch(opcion){\n  case 'add':\n  {\n   winAdicionar" . (String)$con->concepto->denominacion . " = Ext.create('Ext.Window', {\n   title: 'Adicionar',\n   closeAction: 'hide',\n   width: 500,\n    height: 250,\n    x: 220,\n    y: 100,\n    constrain: true,\n    layout: 'fit',\n   buttons: [{\n    text: 'Aplicar',\n    icon:perfil.dirImg + 'aplicar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Aceptar',\n    icon:perfil.dirImg + 'aceptar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Cancelar',\n    icon:perfil.dirImg + 'cancelar.png',\n    handler:function(){\n    winAdicionar" . (String)$con->concepto->denominacion . ".hide();\n    }\n   }\n   ]\n    });\n   winAdicionar" . (String)$con->concepto->denominacion . ".add(form" . (String)$con->concepto->denominacion . ");\n   winAdicionar" . (String)$con->concepto->denominacion . ".show();\n  }break;\n  case 'mod':\n  {\n   winModificar" . (String)$con->concepto->denominacion . " = Ext.create('Ext.Window', {\n   title: 'Modificar',\n   closeAction: 'hide',\n   width: 500,\n    height: 250,\n    x: 220,\n    y: 100,\n    constrain: true,\n    layout: 'fit',\n   buttons: [{\n    text: 'Aplicar',\n    icon:perfil.dirImg + 'aplicar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Aceptar',\n    icon:perfil.dirImg + 'aceptar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Cancelar',\n    icon:perfil.dirImg + 'cancelar.png',\n    handler:function(){\n    winModificar" . (String)$con->concepto->denominacion . ".hide();\n    }\n   }\n   ]\n    });\n   winModificar" . (String)$con->concepto->denominacion . ".add(form" . (String)$con->concepto->denominacion . ");\n   winModificar" . (String)$con->concepto->denominacion . ".show();\n  }break;\n}\n}\n";
        }

        switch ((String)$con->concepto->plantilla) {
            case 'CRUD-Simple':
                $urlG = (String)$con->concepto->urlG;
                $codigo .= $add . $mod . $del . $ucid . $fun . $grid;
                $item = "Gp" . (String)$con->concepto->denominacion;
                break;
            case 'Arbol y Grid':
                $urlG = (String)$con->concepto->urlG;
                $urlA = (String)$con->concepto->urlA;
                $codigo .= $add . $mod . $del . $ucid . $fun . $grid . $arbol;
                $item = "Gp" . (String)$con->concepto->denominacion . ",Tr" . (String)$con->concepto->denominacion;
                break;
        }

        $archivo = $concepto . ".js";
        $fp = fopen($archivo, "w");
        fwrite($fp, "var perfil = window.parent.UCID.portal.perfil;" . PHP_EOL . "UCID.portal.cargarEtiquetas('" . $concepto . "', function(){cargarInterfaz();});" . PHP_EOL . "Ext.QuickTips.init();" . PHP_EOL . "function cargarInterfaz(){" . PHP_EOL . $codigo . PHP_EOL . "var general= Ext.create('Ext.panel.Panel', { title: '" . $concepto . "', layout:'border',items:[" . $item . "]});" . PHP_EOL . "var vpGestSistema =Ext.create('Ext.Viewport',{layout:'fit',items:general});" . PHP_EOL . "}");
        fclose($fp);
        echo "Interfaz generada";
    }
}

?>
