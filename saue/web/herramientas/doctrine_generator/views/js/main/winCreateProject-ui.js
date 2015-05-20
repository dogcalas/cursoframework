DoctrineGenerator.UI.winCreateProject = Ext.extend(Ext.Window, {
    title: 'Crear proyecto',
    width: 615,
    height: 330,
    closable: false,
    layout: 'border',
    closeAction: 'hide',
    modal: true,
    initComponent: function() {
        this.winMapper = new DoctrineGenerator.winMapper({
            scope: this
        })

        this.winProgress = new DoctrineGenerator.winProgress({
            scope: this
        })

        this.smTables = new Ext.grid.CheckboxSelectionModel({
            width: 25,
            scope: this
        });

        this.txtFilter = new Ext.form.TextField({
            enableKeyEvents: true
        })

        this.btnCreateProject = new Ext.Button({
            text: 'Crear proyecto',
            disabled: true
        })

        this.btnConnect = new Ext.Button({
            text: 'Probar conexión'
        })

        this.btnCancel = new Ext.Button({
            text: 'Cancelar'
        })

        this.stTable = new Ext.data.Store({
            url: 'load_tables',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['table_name'])
        })

        this.buttons = [this.btnCancel, this.btnCreateProject]

        this.items = [
            {
                xtype: 'form',
                title: '',
                frame: true,
                region: 'center',
                id: 'form',
                buttons: [this.btnConnect],
                items: [
                    {
                        xtype: 'textfield',
                        id: 'project',
                        fieldLabel: 'Nombre',
                        anchor: '100%',
                        allowBlank: false,
                        blankText: 'Debe ingresar el nombre del proyecto.',
                        vtype: 'alpha',
                        vtypeText: 'Nombre inválido'
                    },
                    {
                        xtype: 'combo',
                        fieldLabel: 'Gestor',
                        anchor: '100%',
                        valueField: 'dbms',
                        displayField: 'dbms',
                        store: new Ext.data.Store({
                            url: 'load_dbms',
                            reader: new Ext.data.JsonReader({
                                root: 'data'
                            }, ['dbms'])
                        }),
                        width: 429,
                        mode: 'remote',
                        triggerAction: 'all',
                        id: 'dbms',
                        allowBlank: false,
                        blankText: 'Debe ingresar el gestor de bases de datos.',
                        forceSelection: true,
                        editable: false
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Servidor',
                        labelSeparator: ':',
                        emptyText: 'Ej: 10.58.11.188',
                        anchor: '100%',
                        width: 442,
                        id: 'host',
                        allowBlank: false,
                        blankText: 'Debe ingresar el IP.'
                    },
                    {
                        xtype: 'numberfield',
                        fieldLabel: 'Puerto',
                        labelSeparator: ':',
                        emptyText: 'Ej: 5800',
                        anchor: '100%',
                        allowBlank: false,
                        blankText: 'Debe ingresar el puerto.',
                        id: 'port'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Usuario',
                        labelSeparator: ':',
                        anchor: '100%',
                        allowBlank: false,
                        blankText: 'Debe ingresar el usuario.',
                        vtype: 'alpha',
                        vtypeText: 'Usuario inválido',
                        id: 'user'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Contrase&ntilde;a',
                        labelSeparator: ':',
                        anchor: '100%',
                        inputType: 'password',
                        width: 451,
                        allowBlank: false,
                        blankText: 'Debe ingresar la contrase&ntilde;a.',
                        id: 'passwd'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Base de datos',
                        labelSeparator: ':',
                        anchor: '100%',
                        allowBlank: false,
                        blankText: 'Debe ingresar la contrase&ntilde;a.',
                        id: 'db'
                    },
                    {
                        xtype: 'combo',
                        fieldLabel: 'Versión',
                        anchor: '100%',
                        id: 'version',
                        mode: 'remote',
                        store: new Ext.data.Store({
                            url: 'load_version',
                            reader: new Ext.data.JsonReader({
                                root: 'data'
                            }, ['version'])
                        }),
                        displayField: 'version',
                        valueField: 'version',
                        allowBlank: false,
                        blankText: 'Debe ingresar la versión.',
                        forceSelection: true,
                        editable: false,
                        triggerAction: 'all'
                    }
                ]
            },
            {
                xtype: 'grid',
                columnResize: false,
                region: 'east',
                id: 'grid',
                sm: this.smTables,
                store: this.stTable,
                tbar: ['->', 'Filtro: ', this.txtFilter],
                width: 300,
                columns: [
                    this.smTables,
                    {
                        header: 'Tabla',
                        width: 270,
                        dataIndex: 'table_name'
                    }
                ]
            }
        ];

        DoctrineGenerator.UI.winCreateProject.superclass.initComponent.call(this);
    }
});
