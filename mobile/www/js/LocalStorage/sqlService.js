app.factory('SQLite_SERVICE', function($q, DB_CONFIG,Info,$rootScope,$state,UIService, $http) {
    var self = this;
    self.db = null;

    
    var baseQuery = function(query, bindings) {
        var deferred = $q.defer();
        try{
           /* if(window.cordova){
                $cordovaSQLite.execute(self.db, query, bindings).then(function(success){
                    deferred.resolve(success);
                },function(error){
                    deferred.reject(error);
                });
            }else{*/
            bindings = typeof bindings !== 'undefined' ? bindings : [];
            self.db.transaction(function(transaction) {
                transaction.executeSql(query, bindings, function(transaction, result) {
                    deferred.resolve(result);
                   // alert(JSON.stringify(result))
                }, function(transaction, error) {
                   // alert("SQL Error - " + error);
                    deferred.reject(error);
                });
            });
       // }
      }catch(err){
           // alert("SQL Error - 2 " + err);
            deferred.reject(err);
      }
      return deferred.promise;
    };

    var fetchAll = function(result) {
        var output = [];

        for (var i = 0; i < result.rows.length; i++) {
            output.push(result.rows.item(i));
        }

        return output;
    };

    var fetch = function(result) {
        return result.rows.item(0);
    };

    //Metods of return

    self.init = function() {
        if(window.cordova){
            try{
                //self.db = $cordovaSQLite.openDB({ name: DB_CONFIG.name });
                self.db =  window.sqlitePlugin.openDatabase({name:DB_CONFIG.name, location: 2});
            }
            catch(err){
                alert(err);
            }
        }else{
          self.db = window.openDatabase( DB_CONFIG.name, "1.0", "FMobile", -1);
        }
        angular.forEach(DB_CONFIG.tables, function(table) {
            var columns = [];
            angular.forEach(table.columns, function(column) {
                columns.push(column.name + ' ' + column.type);
            });
            var query = 'CREATE TABLE IF NOT EXISTS ' + table.name + ' (' + columns.join(',') + ')';
            baseQuery(query).then(
                function(result){
                    console.log(result);
                },function(error){
                    console.log(error);
                });
            console.log('Table ' + table.name + ' initialized');
        });
        self.getActiveMemberUser().then(function (res) {
            if(res.length > 0){
                Info.userInfo ={
                    SessionId : res[0].SessionId,
                    Email: res[0].KullaniciAdi,
                    MyCarDesign: res[0].MyCarDesign
                }
                Info.userInfo.MyCarDesign = Info.userInfo.MyCarDesign.replace(/#ciftTirnak#/g,'"').replace(/#tekTirnak#/g,"'");
                $rootScope.login = true;
                $state.go('tab.myCar');
                $http.get('http://demo.carhub.com/ListMyCar.aspx?AppMode=On&SessionId=' + res[0].SessionId).then(function(resp) {
                    console.log('Success', resp);
                    // For JSON responses, resp.data contains the result
                  }, function(err) {
                    console.error('ERR', err);
                    // err.status will contain the status code
                 })
            }
            else{
                $state.go('login');
            }
            UIService.closeLoading();
        },function(error){
            UIService.popAlert("ERROR",error);
            UIService.closeLoading();            
        })
    };

    self.insertPerfil = function(perfil){
        if( typeof perfil.nickname != 'undefined' && typeof perfil.username != 'undefined' && typeof perfil.password != 'undefined' && typeof perfil.indice != 'undefined'){
            var columns = [];
            for(var i = 1; i < DB_CONFIG.tables[0].columns.length; i++ ) {
                columns.push(DB_CONFIG.tables[0].columns[i].name);
            };
            var query = "INSERT INTO "+DB_CONFIG.tables[0].name+"("+columns.join(',')+") VALUES (?,?,?,?)"
            return baseQuery(query,[perfil.nickname,perfil.username,perfil.password,perfil.indice]);
        }else{
            console.error("SQL - Insira um perfil com nickname,username,password e indice");
            return  null;
        }
    }
    self.updatePerfil = function(perfil){
        if( typeof perfil.id != 'undefined' && typeof perfil.nickname != 'undefined' && typeof perfil.username != 'undefined' && typeof perfil.password != 'undefined' && typeof perfil.indice != 'undefined'){
            if(perfil.id >= 0){
                var columns = [];
                var query = "UPDATE "+DB_CONFIG.tables[0].name+
                " SET "+DB_CONFIG.tables[0].columns[1].name+" = '"+perfil.nickname+"',"
                       +DB_CONFIG.tables[0].columns[2].name+" = '"+perfil.username+"',"
                       +DB_CONFIG.tables[0].columns[3].name+" = '"+perfil.password+"',"
                       +DB_CONFIG.tables[0].columns[4].name+" = "+perfil.indice+" WHERE "+DB_CONFIG.tables[0].columns[0].name+" = "+perfil.id;
                return baseQuery(query);
            }else{
                console.error("SQL - Insira um perfil com id valido");
            }
        }else{
            console.error("SQL - Insira um perfil com id,nickname,username,password e indice");
            return  null;
        }
    }
    self.deletePerfil = function(id){
        if( typeof id != 'undefined' && id != null){
            var columns = [];
            var query = "DELETE FROM "+DB_CONFIG.tables[0].name+" WHERE "+DB_CONFIG.tables[0].columns[0].name+" = "+id;
            return baseQuery(query);
        }else{
            console.error("SQL - Insira um ID");
            return  null;
        }
    }

    self.selectDataById = function(tableName, id){
        var deferred = $q.defer();
        var query =  "SELECT * FROM " + tableName + " WHERE id = "+id;
        baseQuery(query).then(function(success){
            deferred.resolve(fetch(success));
        }, function(error){
            deferred.reject(null);
        });
        return deferred.promise;
    }

    self.AddCurrentDataDetail = function(obdCurrentDet){
        var columns = [];
        for(var i = 1; i < DB_CONFIG.tables[0].columns.length; i++ ) {
            columns.push(DB_CONFIG.tables[0].columns[i].name);
        };
        var query = "INSERT INTO "+DB_CONFIG.tables[0].name+"("+columns.join(',')+") VALUES (?,?,?,?)"
        return baseQuery(query,[obdCurrentDet.nickname,obdCurrentDet.username,obdCurrentDet.password,obdCurrentDet.indice]);
    }

    self.AddCurrentDataMaster = function(obdCurrentMas){
       var columns = [];
        for(var i = 1; i < DB_CONFIG.tables[0].columns.length; i++ ) {
            columns.push(DB_CONFIG.tables[0].columns[i].name);
        };
        var query = "INSERT INTO "+DB_CONFIG.tables[0].name+"("+columns.join(',')+") VALUES (?,?,?,?)"
        return baseQuery(query,[obdCurrentMas.nickname,obdCurrentMas.username,obdCurrentMas.password,obdCurrentMas.indice]);
    }

    self.AddMemberUser = function(memberUser){
       var columns = [];
        for(var i = 1; i < DB_CONFIG.tables[2].columns.length; i++ ) {
            columns.push(DB_CONFIG.tables[2].columns[i].name);
        };
        var query = "INSERT INTO "+DB_CONFIG.tables[2].name+"("+columns.join(',')+") VALUES (?,?,?,?,?,?)"
        return baseQuery(query,[memberUser.KullaniciAdi,memberUser.Sifre,memberUser.Token,memberUser.SessionId,memberUser.IsActive,'']);
    }
    self.getActiveMemberUser = function () {
        var deferred = $q.defer();
        var query =  "SELECT * FROM " + DB_CONFIG.tables[2].name;
        query += " WHERE " + DB_CONFIG.tables[2].columns[5].name + " = 1";
        baseQuery(query).then(function(success){
            deferred.resolve(fetchAll(success));
        }, function(error){
            deferred.reject(null);
        });
        return deferred.promise;
    }
    self.deleteMemberUser = function(){
        var query = "DELETE FROM " + DB_CONFIG.tables[2].name;
        return baseQuery(query);
    }
    self.updateMemberUserDesign = function(memberUser){
        memberUser.myCarDesign = memberUser.myCarDesign.replace(/"/g,'#ciftTirnak#').replace(/'/g,"#tekTirnak#");
        var query = "UPDATE "+DB_CONFIG.tables[2].name+
        " SET "+DB_CONFIG.tables[2].columns[6].name+" = '"+memberUser.myCarDesign+"'"
        +" WHERE "+DB_CONFIG.tables[2].columns[5].name+" = 1";
        return baseQuery(query);
    }

    self.selectAllData = function(tableName){
        var deferred = $q.defer();
        var query =  "SELECT * FROM " + tableName;
        baseQuery(query).then(function(success){
            deferred.resolve(fetchAll(success));
        }, function(error){
            deferred.reject(null);
        });
        return deferred.promise;
    }
    return self;
})
