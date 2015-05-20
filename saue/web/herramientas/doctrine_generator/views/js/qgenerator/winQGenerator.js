DoctrineGenerator.winQGenerator = new Ext.extend(DoctrineGenerator.UI.winQGenerator, {
    loadClass: function(pClas) {
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Espere ..."});
        myMask.show();

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
        DoctrineGenerator.winQGenerator.superclass.initComponent.call(this)
        this.Mask = new Ext.LoadMask(Ext.getBody(), {msg: "Espere ..."});
        this.on('beforehide', function() {
            if (unSave) {
                Ext.MessageBox.confirm('Cerrar', 'Tienes cambios si guardar. ¿Desea continuar?',
                        function(btn) {
                            if (btn == 'yes') {
                                Ext.MessageBox.show({
                                    msg: 'Guardando cambios. Espere...',
                                    progressText: 'Guardando...',
                                    width: 270,
                                    wait: true,
                                    waitConfig: {
                                        interval: 200
                                    },
                                    progress: true,
                                    closable: false,
                                });
                                setTimeout(function() {
                                    Ext.MessageBox.hide();
                                }, 2000);
                            } else
                                return false;
                        });
            }
        });

//                btnQOpen.setHandler(function(r) {
//                    Ext.Ajax.request({
//                        url: '../generate/generate',
//                        params: {
//                            clase: cbClassesQ.getValue()
//                        },
//                        scope: this,
//                        success: function(pResp, pOpt) {
//                            console.log('oook')
//                       }
//                    })
//                    loadClass(r.data.table);
//                }, this)

        btnQParam.setHandler(function() {
            Ext.getCmp('winQParam').show()
        }, this)

        btnQCancel.setHandler(function() {
            this.hide()
        }, this)

        cmbQAction.on('select', function(c, r, r) {
            pIcentral.enable();
            btnQWGuardar.enable()
            if (cmbQAction.getRawValue() == "SELECT") {
                pIoeste.enable()
                //  stQFields.reload()
                stQFields.load(
                        {
                            params: {
                                table: cbClassesQ.getValue()
                            }
                        })
            }
            if (cmbQAction.getRawValue() == "UPDATE" || cmbQAction.getRawValue() == "DELETE") {
                Ext.getCmp('pIoeste').disable()
            }
        }, this)

        txtQFilter.on('keyup', function(tf) {
            if (tf.getValue()) {
                stQTable.filter('table_name', tf.getValue());
            }
        }, this)

        getall.on('check', function(tf) {
            // console.log('123-')
            if (tf.checked) {
                Ext.Ajax.request({
                    url: '../qgenerator/add_function',
                    scope: this,
                    params: {
                        id: 1,
                        type: 'auto',
                        name: 'getCount',
                        visib: 'public',
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            } else {
                Ext.Ajax.request({
                    url: '../qgenerator/rem_function',
                    scope: this,
                    params: {
                        id: 1,
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            }
            treemanageLoader.load(rootmanageNode)
        }, this)

        ckfind.on('check', function(tf) {
            if (tf.checked) {
                Ext.Ajax.request({
                    url: '../qgenerator/add_function',
                    scope: this,
                    params: {
                        id: 2,
                        type: 'auto',
                        name: 'find',
                        visib: 'public',
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            } else {
                Ext.Ajax.request({
                    url: '../qgenerator/rem_function',
                    scope: this,
                    params: {
                        id: 2,
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            }
            treemanageLoader.load(rootmanageNode)
        }, this)

        ckfindAll.on('check', function(tf) {
            if (tf.checked) {
                Ext.Ajax.request({
                    url: '../qgenerator/add_function',
                    scope: this,
                    params: {
                        id: 3,
                        type: 'auto',
                        name: 'findAll',
                        visib: 'public',
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            } else {
                Ext.Ajax.request({
                    url: '../qgenerator/rem_function',
                    scope: this,
                    params: {
                        id: 3,
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            }
            treemanageLoader.load(rootmanageNode)
        }, this)

        ckgetLlave.on('check', function(tf) {
            if (tf.checked) {
                Ext.Ajax.request({
                    url: '../qgenerator/add_function',
                    scope: this,
                    params: {
                        id: 4,
                        type: 'auto',
                        name: 'getKey',
                        visib: 'public',
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            } else {
                Ext.Ajax.request({
                    url: '../qgenerator/rem_function',
                    scope: this,
                    params: {
                        id: 4,
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            }
            treemanageLoader.load(rootmanageNode)
        }, this)

        cksave.on('check', function(tf) {
            if (tf.checked) {
                Ext.Ajax.request({
                    url: '../qgenerator/add_function',
                    scope: this,
                    params: {
                        id: 5,
                        type: 'auto',
                        name: 'save',
                        visib: 'public',
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            } else {
                Ext.Ajax.request({
                    url: '../qgenerator/rem_function',
                    scope: this,
                    params: {
                        id: 5,
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            }
            treemanageLoader.load(rootmanageNode)
        }, this)

        ckGenLimit.on('check', function(tf) {
            if (ckGenLimit.checked) {
                if (Ext.getCmp('tfstart').getValue() != null && Ext.getCmp('tflimit').getValue() != null) {
                    Ext.Ajax.request({
                        url: '../qgenerator/add_function',
                        scope: this,
                        params: {
                            id: 8,
                            type: 'auto',
                            name: 'xLimite',
                            visib: 'public',
                            table: Ext.getCmp('cbClassesQ').getValue(),
                            start: Ext.getCmp('tfstart').getValue(),
                            limit: Ext.getCmp('tflimit').getValue()
                        }
                    })
                } else {
                    ckGenLimit.setValue(false)
                }
            } else {
                Ext.Ajax.request({
                    url: '../qgenerator/rem_function',
                    scope: this,
                    params: {
                        id: 8,
                        table: Ext.getCmp('cbClassesQ').getValue()
                    }
                })
            }
            treemanageLoader.load(rootmanageNode)
        }, this)

        txtQFilter.on('keyup', function(tf) {
            if (tf.getValue()) {
                stQFields.filter('name', tf.getValue());
            } else {
                stQFields.reload()
            }
        }, this)

        cmbQtipJoin.on('select', function(c, r, i) {
            if (cbClassesQJ.getValue() != "") {
                btnQJAdd.enable()
            }
        }, this)

        cbClassesQJ.on('select', function(c, r, i) {
            if (cmbQtipJoin.getValue() != "") {
                btnQJAdd.enable()
            }
            stQFields.load({
                params: {
                    table: cbClassesQ.getValue(),
                    table1: cbClassesQJ.getValue()
                }
            })
            smQWFields.load({
                params: {
                    table: cbClassesQ.getValue(),
                    table1: cbClassesQJ.getValue()
                }
            })
        }, this)

        cmbQCampo.on('focus', function(r) {
            alert('ok')
            stQWFields.load({
                params: {
                    table: cbClassesQ.getValue(),
                    table1: cbClassesQJ.getValue()
                }
            })
        }, this)

        Ext.getCmp('fswhere').on('expand', function(c) {
            stQWFields.load({
                params: {
                    table: cbClassesQ.getValue(),
                    table1: cbClassesQJ.getValue()
                }
            })
        }, this)

        cbClassesQ.on('select', function(c, r, i) {
            var mmask = new Ext.LoadMask(Ext.getBody(), {msg: "Espere ..."});
            mmask.show()
            pcentral.disable()
            pIcentral.disable();
            poeste.disable()
            psur.disable()
            btnQEdit.enable()
            clearSelection()
            stQFields.load(
                    {
                        params: {
                            table: cbClassesQ.getValue()
                        }
                    });
            stQWFields.load({
                params: {
                    table: cbClassesQ.getValue(),
                    table1: cbClassesQJ.getValue()
                }
            })
            Ext.Ajax.request({
                url: '../qgenerator/load_check',
                params: {
                    clase: cbClassesQ.getValue()
                },
                scope: this,
                success: function(pResp, pOpt) {
                    var objJson = Ext.util.JSON.decode(pResp.responseText);
                    for (var i = 0; objJson.length; i++) {

                        if (objJson[i].id == "1") {
                            getall.setValue(true)
                        }
                        if (objJson[i].id == "2") {
                            ckfind.setValue(true)
                        }
                        if (objJson[i].id == 3) {
                            ckfindAll.setValue(true)
                        }
                        if (objJson[i].id == 4) {
                            ckgetLlave.setValue(true)
                        }
                        if (objJson[i].id == 5) {
                            cksave.setValue(true)
                        }
                        if (objJson[i].id == 8) {
                            ckGenLimit.setValue(true)
                            fsXlimite.toggleCollapse(false)
                        }
                    }

                }

            })
            mmask.hide()
            Ext.Ajax.request({
                url: '../qgenerator/load_version',
                scope: this,
                success: function(pResp, pOpt) {
                    var version = Ext.util.JSON.decode(pResp.responseText)
                    tfversion.setValue(version)
                    if (version == "DoctrineV2") {
                        textnames.enable()
                    }else{
                        textnames.disable()
                    }
                }
            })
            Ext.Ajax.request({
                url: '../qgenerator/load_alias',
                params: {
                    clase: cbClassesQ.getValue()
                },
                scope: this,
                success: function(pResp, pOpt) {
                    var alias = Ext.util.JSON.decode(pResp.responseText)

                    textQAlias.setValue(alias)

                }
            })
            Ext.Ajax.request({
                url: '../qgenerator/load_namespace',
                params: {
                    clase: cbClassesQ.getValue()
                },
                scope: this,
                success: function(pResp, pOpt) {
                    var namespace = Ext.util.JSON.decode(pResp.responseText)
                    if (namespace != null)
                        textnames.setValue(namespace)
                }
            })
        }, this)

        cmbQValor.on('focus', function(c, r, i) {
            stQFieldsV.load(
                    {
                        params: {
                            table: cbClassesQ.getValue()
                        }
                    });

        }, this)

        btnQOpen.setHandler(function() {
            Ext.Ajax.request({
                url: '../generate/generate',
                success: function() {
                    window.open('../generate/descargar')
                }
            })
        }, this)
        btnQEdit.setHandler(function() {
            if (tfversion.getValue() == "DoctrineV2") {
                if (textnames.getValue() != "") {
                    Ext.Ajax.request({
                        url: '../qgenerator/set_namespace',
                        params: {
                            table: cbClassesQ.getValue(),
                            namespace: textnames.getValue()
                        },
                        success: function() {
                            pcentral.enable()
                            pIcentral.disable();
                            poeste.enable()
                            psur.enable()
                        }
                    })
                } else {
                    Ext.MessageBox.show({title: 'Alerta', msg: 'Debe introducir el namespace.'})
                }
            } else {
                pcentral.enable()
                pIcentral.disable();
                poeste.enable()
                psur.enable()
            }

        }, this)

        function clearSelection() {
            stQFields.reload()
            getall.setValue(false)
            ckGenLimit.setValue(false)
            ckfind.setValue(false)
            ckfindAll.setValue(false)
            ckgetLlave.setValue(false)
            ckorderByASC.setValue(false)
            cksave.setValue(false)
            ckGenLimit.setValue(false)
            textnames.setValue("")
            fsXlimite.toggleCollapse(true)
        }
    }
})