DoctrineGenerator.pRelations = Ext.extend (DoctrineGenerator.UI.pRelations, {
    toggleEnabledCombo : function (pStatus) {
        this.cbClasses.setDisabled (! pStatus);
    },
    load: function (pTable) {
        if (pTable.parent_table) {
            this.chkInherits.setValue (true);
            this.cbClasses.setValue (pTable.parent_table);
        } else {
            this.chkInherits.setValue (' ');
            this.cbClasses.setDisabled (true);
        }   
    
        this.stRelations.loadData (pTable)
    },
    updateRelation: function (pEvent, pTable) {
    	var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Espere ..."});
        myMask.show();
    	
    	Ext.Ajax.request ({
            url: '../mapper/update_relation',
            scope: this,
            params:  {
                value: pTable,
                row: pEvent.row,
                clase: this.table
            },
            success: function (r, o) {
                obj = Ext.decode (r.responseText)

                if (obj.fields) {
                    pEvent.record.commit ();
                    o.scope.stRelations.loadData (obj)
                    myMask.hide ();
                } else {
                    pEvent.record.reject ();
                }
            }
        })
    },
    updateParentClass : function (pClass) {
        Ext.Ajax.request ({
            url : '../mapper/update_parent',
            scope: this,
            params: {
                parent: pClass,
                table: this.table,
                index: this.index
            },
            success: function (pResp, pOpt) {
                obj = Ext.decode (pResp.responseText)
                pOpt.scope.stRelations.loadData (obj)
            }
        })
    },
    remRelation: function (pRelation) {
    	sm = this.egRelations.getSelectionModel ()
    	pRelation = sm.getSelected ().data
    	
    	if (sm.getCount ()) {
            pRelation.table = this.table;

            cant = this.egRelations.store.getCount ()
            selected = -1;

            for (i = 0; i < cant; i++) {
                if (this.egRelations.getSelectionModel ().isSelected (i)) {
                    selected = i;break;
                }
            }

            pRelation.index = selected;

            Ext.Ajax.request ({
                url: '../mapper/rem',
                params: pRelation,
                scope: this,
                success: function (pResp, o) {
                    obj = Ext.decode (pResp.responseText)
                    o.scope.stRelations.loadData (obj)
                }
            })
    	} else Ext.Msg.show ({ 
                                msg: 'Debe seleccionar una tupla',
                                title: 'Doctrine Generator',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
    },
    initComponent: function () {
        DoctrineGenerator.pRelations.superclass.initComponent.call (this);
        
        this.chkInherits.on ('check', function (ch, s) {
            this.toggleEnabledCombo (s);
        }, this)

        this.cbForeignTable.on  ('select', function (c, r, i) {
            c.table = c.store.getAt (i).data.table;
        })

        this.egRelations.on ('afteredit', function (e) {
            this.updateRelation (e, this.cbForeignTable.table)
        }, this)

        this.cbClasses.on ('beforeselct', function () {
            if (r.data.clas == this.clase) {
                Ext.Msg.show ({msg: 'No se puede heredar de si mismo',
                               icon: Ext.MessageBox.ERROR,
                               buttons: Ext.MessageBox.OK})
                return false;
            }
        })

        this.cbClasses.on ('select', function (c, r, i) {
            this.updateParentClass(r.data.clas)
        }, this)
        
        this.btnRemRelation.setHandler (function () {
        	this.remRelation ()
        }, this)
        
        this.btnAddRelation.setHandler (function () {
        	this.winRelations.table = this.table
        	this.winRelations.show ()
        }, this)
    }
});