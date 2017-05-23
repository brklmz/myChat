app.constant('DB_CONFIG', {
    name: 'capAppV2.db',
    tables: [
        {
            name: 'currentDataMaster',
            columns: [
                {name: 'id', type: 'integer primary key autoincrement'},
                {name: 'VINNumber', type:'text'},
                {name: 'startPosition', type: 'text'},
                {name: 'stopPosition', type: 'text'},
                {name: 'InsertDate', type: 'datetime'},
                {name: 'UpdateDate', type: 'datetime'}
            ]
        },
        {
            name: 'currentDataDetail',
            columns: [
                {name: 'id', type: 'integer primary key autoincrement'},
                {name: 'MasterId', type:'integer'},
                {name: 'PidCmd', type: 'text'},
                {name: 'Value', type: 'text'},
                {name: 'InsertDate', type: 'datetime'},
                {name: 'UpdateDate', type: 'datetime'}
            ]
        },
        {
            name: 'memberUser',
            columns: [
                {name: 'id', type: 'integer primary key autoincrement'},
                {name: 'KullaniciAdi', type: 'text'},
                {name: 'Sifre', type: 'text'},
                {name: 'Token', type: 'text'},
                {name: 'SessionId', type: 'text'},
                {name: 'IsActive', type: 'integer'},
                {name: 'MyCarDesign', type: 'text'}

            ]
        },
        {
            name: 'widgets',
            columns: [
                {name: 'id', type: 'integer autoincrement'},
                {name: 'widgetId', type: 'integer primary key'},
                {name: 'widgetContent', type: 'text'},
                {name: 'widgetCol', type: 'integer'},
                {name: 'widgetRow', type: 'integer'},
                {name: 'widgetX', type: 'integer'},
                {name: 'widgetY', type: 'integer'}

            ]
        }
    ]
});