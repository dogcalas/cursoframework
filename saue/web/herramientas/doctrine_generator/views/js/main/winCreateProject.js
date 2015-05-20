DoctrineGenerator.winCreateProject = Ext.extend(DoctrineGenerator.UI.winCreateProject, {
    connect: function(pHost, pDBMS, pUser, pPasswd, pPort, pDb, pVersion) {
        if (pHost && pDBMS && pUser && pPasswd && pPort && pDb && pVersion) {
            this.Mask.show();

            Ext.Ajax.request({
                url: 'connect',
                params: {
                    dbms: pDBMS,
                    user: pUser,
                    passwd: pPasswd,
                    port: pPort,
                    host: pHost,
                    db: pDb,
                    version: pVersion
                },
                scope: this,
                success: function(pResp) {
                    decoded = Ext.decode(pResp.responseText);

                    if (!decoded.codMsg) {
                        this.stTable.loadData(decoded);
                    }

                    this.Mask.hide()
                },
                failure: function() {
                    this.Mask.hide()
                }
            })
        } else
            Ext.MessageBox.show({
                title: 'Doctrine Generator v3.0',
                msg: 'Debe completar todos los campos.',
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK
            })
    },
    createProject: function(pName, pVersion, pTables) {
        if (!pName || !pVersion) {
            Ext.MessageBox.show({
                title: 'Doctrine Generator v3.0',
                msg: 'Debe completar todos los campos.',
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK
            })
            return;
        }

        if (pTables.length > 0) {
            this.winProgress.show()

            tables = []
            var num = 1;
            for (i = 0; i < pTables.length; i++)
                tables.push(pTables[i].data.table_name);


            this.winProgress.cant = tables.length * 3
            this.winProgress.rest = tables.length * 3

            this.addTable(tables, num)

        } else
            Ext.MessageBox.show({
                title: 'Doctrine Generator v3.0',
                msg: 'Debe seleccionar al menos una tabla.',
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK
            })
    },
    addRelations: function(pTables) {
        table = pTables.pop()

        this.winProgress.update()

        if (table) {
            Ext.Ajax.request({
                url: 'add_relations',
                scope: this,
                params: {name: table},
                success: function(pResp, pOpt) {
                    pOpt.scope.addRelations(pTables)
                }
            })
        } else {
            tables = []
            sm = this.smTables.getSelections()

            for (i = 0; i < sm.length; i++)
                tables.push(sm[i].data.table_name);

            this.reverseRelations(tables)
        }
    },
    addTable: function(pTables, num) {
        table = pTables.pop()

        this.winProgress.update()

        if (table) {
            Ext.Ajax.request({
                url: 'add_table',
                scope: this,
                params: {name: table,
                    num: num},
                success: function(pResp, pOpt) {
                    num++;
                    pOpt.scope.addTable(pTables, num)
                }
            })
        } else {
            tables = []
            sm = this.smTables.getSelections()

            for (i = 0; i < sm.length; i++)
                tables.push(sm[i].data.table_name);


            this.addRelations(tables)
        }
    },
    setVersion: function(pProject, pVersion) {
        Ext.Ajax.request({
            url: 'create_project',
            scope: this,
            params: {
                project: pProject,
                version: pVersion
            },
            success: function(pResp, pOpt) {
                this.Mask.hide();
                Ext.Msg.show({
                    title: 'Doctrine Generator v3.0',
                    msg: 'El proyecto se ha creado correctamente.',
                    icon: Ext.MessageBox.INFO,
                    scope: this,
                    buttons: Ext.MessageBox.OK,
                    fn: function(b, t, o) {
                        this.hide()
                        this.winProgress.hide()
                        if (qgenera) {
                            this.winQGenerator = new DoctrineGenerator.winQGenerator({
                                scope: this
                            })
                            this.winQGenerator.show()
                        } else {
                            this.winMapper.show();
                        }
                    }
                })
            }
        })
    },
    reverseRelations: function(pTables) {
        table = pTables.pop()

        this.winProgress.update()

        if (table) {
            Ext.Ajax.request({
                url: 'revert_relation',
                scope: this,
                params: {name: table},
                success: function(pResp, pOpt) {
                    pOpt.scope.reverseRelations(pTables)
                }
            })
        } else {
            this.setVersion(Ext.getCmp('project').getValue(),
                    Ext.getCmp('version').getValue())
        }
    },
    initComponent: function() {
        DoctrineGenerator.winCreateProject.superclass.initComponent.call(this)

        this.Mask = new Ext.LoadMask(Ext.getBody(), {msg: "Espere ..."});

        this.btnConnect.setHandler(function() {
            this.connect(Ext.getCmp('host').getValue(),
                    Ext.getCmp('dbms').getValue(),
                    Ext.getCmp('user').getValue(),
                    Ext.getCmp('passwd').getValue(),
                    Ext.getCmp('port').getValue(),
                    Ext.getCmp('db').getValue(),
                    Ext.getCmp('version').getValue());
        }, this)

        this.btnCreateProject.setHandler(function() {
            this.createProject(Ext.getCmp('project').getValue(),
                    Ext.getCmp('version').getValue(),
                    this.smTables.getSelections())
        }, this)

        this.btnCancel.setHandler(function() {
            if (qgenera) {
                this.winQGenerator = new DoctrineGenerator.winQGenerator({
                    scope: this
                })
                this.winQGenerator.show()
                this.hide();
            } else {
                Ext.getCmp('form').getForm().reset();
                Ext.getCmp('grid').getStore().removeAll();
                this.hide();
            }
        }, this)

        this.txtFilter.on('keyup', function(tf) {
            if (tf.getValue()) {
                this.stTable.filter('table_name', tf.getValue());
            }
        }, this)

        this.smTables.on('selectionchange', function(pSM) {
            pSM.scope.btnCreateProject.setDisabled(!pSM.hasSelection())
        })
    }
})