Ext.define('Ext.ux.form.SearchComboField', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.searchcombofield',

    editable: true,
    typeAhead: true,
    forceSelection: true,
    trigger1Cls: Ext.baseCSSPrefix + 'form-clear-trigger',
    trigger2Cls: Ext.baseCSSPrefix + 'x-form-arrow-trigger',
    queryMode: 'local',
    hasSearch: false,
    filterParamName: 'filtros',

    initComponent: function () {
        var me = this;

        me.callParent(arguments);

        // We're going to use filtering and configured a filterParam in case is dosen't exists
        if (Ext.isArray(me.storeToFilter)) {
            for (var i = 0; i < me.storeToFilter.length; i++) {
                if (Ext.isString(me.storeToFilter[i]))
                    me.storeToFilter[i] = Ext.data.StoreManager.lookup(me.storeToFilter[i]);
                me.storeToFilter[i].remoteFilter = true;
                if (!me.storeToFilter[i].proxy.hasOwnProperty('filterParam'))
                    me.storeToFilter[i].proxy.filterParam = me.filterParamName;
            }
        } else {
            if (Ext.isString(me.storeToFilter)) {
                me.storeToFilter = Ext.data.StoreManager.lookup(me.storeToFilter);
            }
            me.storeToFilter.remoteFilter = true;
            if (!me.storeToFilter.proxy.hasOwnProperty('filterParam')) {
                me.storeToFilter.proxy.filterParam = me.filterParamName;
            }
            ;
        }

        if (me.filterPropertysNames == null) {
            me.filterPropertysNames = [me.valueField];
        }
        ;

        //Creando los filtros por cada filterPropertysNames definido
        me.filtros = Ext.create('Ext.util.MixedCollection');

        me.on(
            {
                select: me.onSelect
            }
        );
    },

    afterRender: function () {
        var me = this;
        me.callParent();
        me.triggerCell.item(0).setDisplayed(false);
        me.updateLayout();
    },

    onTrigger1Click: function (load) {
        var me = this,
            cargar = (Ext.isObject(load)) ? true : false;

        if (me.hasSearch) {
            me.setValue('');
            //me.store.clearFilter();
            me.filtros.each(
                function (filtro) {
                    if (Ext.isArray(me.storeToFilter)) {
                        for (var i = 0; i < me.storeToFilter.length; i++) {
                            me.storeToFilter[i].filters.eachKey(
                                function (key, filter) {
                                    if (filtro.id === key) {
                                        me.storeToFilter[i].filters.remove(filter);
                                    }
                                }
                            )
                        }
                    } else
                        me.storeToFilter.filters.eachKey(
                            function (key, filter) {
                                if (filtro.id === key) {
                                    me.storeToFilter.filters.remove(filter);
                                }
                            }
                        )
                }
            )
            /*me.filtros.each(
             function (filtro) {
             console.log();
             me.storeToFilter.removeFilter(filtro);
             }
             )*/
            if (cargar) {
                if (Ext.isArray(me.storeToFilter)) {
                    for (var i = 0; i < me.storeToFilter.length; i++) {
                        me.storeToFilter[i].load();
                    }
                } else
                    me.storeToFilter.load();
            }
            me.hasSearch = false;
            me.triggerCell.item(0).setDisplayed(false);
            me.updateLayout();
        }
    },

    onTrigger2Click: function () {
        this.expand();
    },

    onSelect: function (combo) {
        var me = this,
            value = combo.getValue();

        if (value != null) {

            if (me.filterPropertysNames.length > 0) {
                for (var i = 0; i < me.filterPropertysNames.length; i++) {
                    me.filtros.add(i, Ext.create('Ext.util.Filter', {
                        id: 'id' + me.filterPropertysNames[i],
                        property: me.filterPropertysNames[i],
                        value: value
                    }))
                }
            }

            // Param name is ignored here since we use custom encoding in the proxy.
            // id is used by the Store to replace any previous filter
            me.filtros.each(
                function (filtro, index, len) {
                    if (Ext.isArray(me.storeToFilter)) {
                        for (var i = 0; i < me.storeToFilter.length; i++) {
                            me.storeToFilter[i].addFilter(
                                [
                                    filtro
                                ], (index == len - 1)
                            );
                        }
                    } else
                        me.storeToFilter.addFilter(
                            [
                                filtro
                            ], (index == len - 1)
                        );
                }
            )

            me.hasSearch = true;
            me.triggerCell.item(0).setDisplayed(true);
            me.updateLayout();
        }
    }
})