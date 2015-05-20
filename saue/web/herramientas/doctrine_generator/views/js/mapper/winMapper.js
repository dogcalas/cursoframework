DoctrineGenerator.winMapper = Ext.extend(DoctrineGenerator.UI.winMapper, {
    loadClass: function(pClas) {

        var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Espere ..."});
        myMask.show();
        var version = ""

        Ext.Ajax.request({
            url: '../mapper/load',
            params: {
                clase: pClas
            },
            scope: this,
            success: function(pResp, pOpt) {
                obj = Ext.decode(pResp.responseText);
                pOpt.scope.stFields.loadData(obj);

                pOpt.scope.pRelations.load(obj);
                myMask.hide();
                Ext.getCmp('tp').setDisabled(false);
            }
        })
    },
    initComponent: function() {
        DoctrineGenerator.winMapper.superclass.initComponent.call(this);

        this.cbClasses.on('select', function(c, r, i) {
            this.loadClass(r.data.table);
            this.pRelations.setDisabled(false)
            this.pRelations.table = r.data.table;
            this.pRelations.clase = r.data.clas
    
        }, this)
        function addCheckB(names) {
            var ck = {
                // xtype: 'checkbox',
                fieldLabel: names,
                id: names,
                name: names,
                // allowBlank: false
            }
            return  ck;
        }

       this.btnQGenO.setHandler(function() {

            this.winQGenerator.show()
        }, this)


        this.btnCancel.setHandler(function() {
            this.hide();
        }, this)
        this.btnMap.setHandler(function() {
            Ext.Ajax.request({
                url: '../generate/generate',
                success: function() {
                    window.open('../generate/descargar')
                }
            })
        }, this)
    }

});
function DinamicCheck(name) {
    new Ext.form.Checkbox({
        fieldLabel: name,
        id: name,
        name: name,
        anchor: '90%'
    })
}