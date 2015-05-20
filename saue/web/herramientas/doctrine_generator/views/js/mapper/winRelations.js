DoctrineGenerator.winRelations = Ext.extend(DoctrineGenerator.UI.winRelations, {
	initComponent: function () {
    	DoctrineGenerator.winRelations.superclass.initComponent.call(this);
    	
    	this.cbForeignTable.on ('select', function (c, r, i) {
    		this.stForeignField.load ({
    			params: {
    				table: r.data.table
    			}
    		})
    	}, this)
    	
    	this.btnOk.setHandler (function () {
    		f = this.frmRelation.getForm ()
                
                ft = this.cbForeignTable.getValue ()
                type = this.cbType.getValue ()
                ff = this.cbForeignField.getValue ()
                lf = this.cbLocalField.getValue ()
                it = this.cbIntermediateTable.getValue ()

    		if (f.isValid ())
    			Ext.Ajax.request ({
    				url: '../mapper/add',
                                scope: this,
    				params: {
    					type:type,
    					ft: ft,
                                        ff: ff, 
    					lf:lf,
    					lt: this.table,
    					it: it
    				},
                                success: function (pResp, pOpt) {
                                    obj = Ext.decode (pResp.responseText)
                                    pOpt.scope.scope.stRelations.loadData (obj)
                                    pOpt.scope.frmRelation.getForm ().reset ()
                                    pOpt.scope.hide ()
                                }
    			})
    	}, this)
    	
    	this.btnCancel.setHandler (function () {
    		this.hide ()
    	}, this)
    }
});
