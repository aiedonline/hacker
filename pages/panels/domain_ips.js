

function load_domain_ips(){
    EdbList("ip", ["domain_id"], [Parameter("id")], function(){}, undefined, undefined, function(data, status, erro) {
        if(data == undefined){
            return;
        }

        data.sort(function(a, b){ if( a.ip > b.ip ) {return 1; } else {return -1;} });
        AddRowTable("IPs", [{"field" : "report", "text" : "Rep."},{"field" : "ip", "text" : "IP do host"}, {"field" : "geo", "text" : "Localização"}, {"field" : "port", "text" : "Portas"}, {"field" : "relatorio", "text" : "Relatório"}],
             data, "tbl_ips_domains", function (row, key, value, data, j , i){
                if(key == "report"){
                    if(value == 1){
                        return "Sim";
                    }
                    return "";
                }
                if(key == "relatorio"){
                    var buffer = "";
                    // , host, is_crawler, mobile, proxy, vpn, tor, active_vpn, active_tor, recent_abuse, ,
                    if(data.fraud_score){
                        var cor = "red";
                        if(data.fraud_score >= 85){
                            cor = "red";
                        } else if(data.fraud_score >= 75){
                            cor = "orange";
                        } else{
                            cor = "green";
                        }
                        buffer += "<b><font color='"+ cor +"'>Score: " + data.fraud_score +  "</font></b>";
                    }
                    if(data.recent_abuse == 1){
                        buffer +=  (buffer != "" ? ", " : "") + "<b><font color='red'>ABUSO RELATADO</font></b>";
                    }
                    if(data.proxy == 1){
                        buffer +=  (buffer != "" ? ", " : "") + "<b>Proxy</b>";
                    }
                    if(data.vpn == 1){
                        buffer +=  (buffer != "" ? ", " : "") + "<b>VPN</b>";
                    }
                    if(data.tor == 1){
                        buffer +=  (buffer != "" ? ", " : "") + "<b>TOR</b>";
                    }
                    return buffer;
                }
                return "<a href='/secanalysis/pages/virtual.php?entity=ip&layout=cadastrar&id="+ data._id +"&domain_id="+ Parameter("id") +"'>" + value + "</a>";
             }, undefined, function(table_id, row_id, index, data){
                EdbList("ip_port", ["ip_id"], [ data._id ], function(){}, undefined, undefined, function(data_list, status, erro) {
                    if(data_list == undefined){
                        return;
                    }
                    //port, evidence, ip_id, font, date_create, protocol, _user, report, host, ,
                    var protocolos = {};
                    for(var i = 0; i < data_list.length; i++){
                        var buffer_protocol = "undefined";
                        if(data_list[i]["report"] != ""){
                    //        //e": "open", "reason": "syn-ack", "name": "pop3", "product": "Dovecot pop3d", "version": "", "extrainfo": "", "conf": "10", "cpe": "cpe:/a:dovecot:dovecot"}
                            var buffer_json = JSON.parse(data_list[i]["evidence"]);
                            if(protocolos[ buffer_json.name  ] == undefined) protocolos[ buffer_json.name  ] = [];
                            protocolos[ buffer_json.name  ].push(data_list[i]["port"]);
                        }
                    }
                    var buffer_texto = "";
                    console.log(protocolos);
                    var keys = Object.keys(protocolos);
                    for (var i = 0; i < keys.length; i++ ) {
                        var key = keys[i];
                        //console.log(key + " -> " + p[key])
                        buffer_texto += key;
                        if(i < keys.length - 1){
                            buffer_texto += ", ";
                        }
                        //for(var i = 0; i < protocolos[key].length; i++){
                        //    buffer_json += protocolos[key][i];
                        //    if(i < protocolos[key].length - 1 ){
                        //        buffer_json += ", ";
                        //    } else {
                        //        buffer_json += "<br/>";
                        //    }
                        //}
                    }
                    console.log(row_id, buffer_texto);
                    $("#" + row_id + "_3").html(buffer_texto);
                });
             });
        AddRow("IPs", [8, 4], undefined, "div_domain_ip_linhe");
        AddButton("div_domain_ip_linhe_1", "Adicionar novo IP", "btn_add_domain_ip_click", "btn_add_domain_ip", {"type" : "warning"})

    }, cache, {"order" : [{"field" : "ip", "order" : "desc"}]});

    

}

function btn_add_domain_ip_click(){
    window.location.href = "/secanalysis/pages/virtual.php?entity=ip&layout=cadastrar&domain_id=" + Parameter("id");
}
