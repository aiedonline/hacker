

// ----------=========== IPQUALITY ================-----------------
function ipquality_options_load(div){

    EdbRead("project_ipquality_arguments", ["project_id"], [Parameter("id")], function(saida, erro, status){
        AddRadioGroup(div, "rd_ipquality_enable", [{"value" : 0, "field" : "Não executar" }, {"value" : 1 , "field" : "Executar"}], "field", "value" , false);
        AddText(div, "text", "Key", "Key obtida no site do ipquality", "txt_ipquality_key");
        AddRow(div, [8, 4], undefined, "div_ipquality_button");
        AddButton("div_ipquality_button_1", "Salvar parametros ipquality.io", "btn_ipquality_salvar_click", "btn_ipquality_salvar", {"type" : "warning"})
        
        if(saida == undefined){
            EdbWrite(["project_ipquality_arguments"], [["_id", "project_id", "enable", "ipquality_key"]], [[Parameter("id"), Parameter("id"), 0, ""]], function(saida, status, erro){
                rd_ipquality_enable.val(0);
                txt_ipquality_key.val("");
            });
        } else{
            rd_ipquality_enable.val(saida['enable']);
            txt_ipquality_key.val(saida['ipquality_key']);
            
        }
    });
}

function btn_ipquality_salvar_click(){
    EdbWrite(["project_ipquality_arguments"], [["_id", "project_id", "enable", "ipquality_key"]], 
                [[Parameter("id"), Parameter("id"), rd_ipquality_enable.val()[0], txt_ipquality_key.val()]], function(saida, status, erro){
        console.log(saida, status, erro);
        alert("sucesso");
    });

}

// ----------=========== SHODAN ================-----------------
function shodan_options_load(div){

    EdbRead("project_shodan_arguments", ["project_id"], [Parameter("id")], function(saida, erro, status){
        AddRadioGroup(div, "rd_shodan_enable", [{"value" : 0, "field" : "Não executar" }, {"value" : 1 , "field" : "Executar"}], "field", "value" , false);
        AddText(div, "text", "Key", "Key obtida no site do shodan.io", "txt_shodan_key");
        AddRow(div, [8, 4], undefined, "div_shodan_button");
        AddButton("div_shodan_button_1", "Salvar parametros Shodan.io", "btn_shodan_salvar_click", "btn_shodan_salvar", {"type" : "warning"})
        
        if(saida == undefined){
            EdbWrite(["project_shodan_arguments"], [["_id", "project_id", "enable", "shodan_key"]], [[Parameter("id"), Parameter("id"), 0, ""]], function(saida, status, erro){
                rd_shodan_enable.val(0);
                txt_shodan_key.val("");
            });
        } else{
            rd_shodan_enable.val(saida['enable']);
            txt_shodan_key.val(saida['shodan_key']);
            
        }
    });
}

function btn_shodan_salvar_click(){
    EdbWrite(["project_shodan_arguments"], [["_id", "project_id", "enable", "shodan_key"]], 
                [[Parameter("id"), Parameter("id"), rd_shodan_enable.val()[0], txt_shodan_key.val()]], function(saida, status, erro){
        console.log(saida, status, erro);
        alert("sucesso");
    });

}

// ----------========== EXECUÇOES ======================---------------------
function update_execution_log(){
    if( $("#tbl_executions").length > 0 ){
        $("#tbl_executions").remove();
    }

    EdbList("execution", [], [], function(){}, undefined, undefined, function(data, status, erro) {
        if(data == undefined){
            return;
        }

        data.sort(function(a, b){ if( a.date_execution > b.date_execution ) {return -1; } else {return 1;} });
        AddRowTable("div_execution_commandline", [{"field" : "date_execution", "text" : "Data Execução"}, {"field" : "ip", "text" : "IP do cliente"}, {"field" : "status", "text" : "Status"},  {"field" : "status_code", "text" : "Código de saída"}],
             data, "tbl_executions", function (row, key, value, data, j , i){
                 if(value == null) return "";
                 if(key == "status"){
                     if(value == 0){
                        return "Em execução";
                     } else {
                         return "Finalizado";
                     }
                 }
                 return "<a href='JavaScript:AbrirLog(\""+ Base64Encode( JSON.stringify(data) ) +"\")'>"+ value +"</a>";
             })
    }, cache, {"order" : [{"field" : "date_execution", "order" : "desc"}]});
}
function execucoes_load(div){

    AddRow(div, [8, 4], undefined, "div_execute_panel_row");
    AddButton("div_execute_panel_row_1", "Atualizar Lista", "btn_update_execute_salvar_click", "btn_update_execute_salvar", {})
    update_execution_log();
}

function btn_update_execute_salvar_click(){
    update_execution_log();
}

function AbrirLog(dados){
    var dados = JSON.parse( Base64Decode(dados) );
    div = AddModal("raiz", "Adicionar", function(){
		
	}, function(){} , "div_show_output");
    div.html(dados.stdout.replaceAll("\n", "<br/>") + " - " + dados.stderr.replaceAll("\n", "<br/>"));
}

// ----------=========== NMAP AMBIENTE ================-----------------
function nmap_options_load(div){
    var DEFAULT_NMAP_ARGUMENTS = "-sV -O"
    EdbRead("project_nmap_arguments", ["project_id"], [Parameter("id")], function(saida, erro, status){
        AddRadioGroup(div, "rd_nmap_enable", [{"value" : 0, "field" : "Não executar" }, {"value" : 1 , "field" : "Executar"}], "field", "value" , false);
        AddText(div, "text", "Argumentos", "Os argumentos NMAP, acesse livro Hacker entre a luz e as trevas", "txt_nmap_arguments");
        AddRow(div, [8, 4], undefined, "div_nmap_button");
        
        AddButton("div_nmap_button_1", "Salvar parametros Nmap", "btn_nmap_salvar_click", "btn_nmap_salvar", {"type" : "warning"})
        
        if(saida == undefined){
            EdbWrite(["project_nmap_arguments"], [["_id", "project_id", "enable", "arguments"]], [[Parameter("id"), Parameter("id"), 0, DEFAULT_NMAP_ARGUMENTS]], function(saida, status, erro){
                rd_nmap_enable.val(0);
                txt_nmap_arguments.val(DEFAULT_NMAP_ARGUMENTS);
            });
            
        } else{
            rd_nmap_enable.val(saida['enable']);
            txt_nmap_arguments.val(saida['arguments']);
        }
    });
}

function btn_download_bot_click(){
    window.location.href = "/secanalysis/download/bot.tar.gz";
}

function commandline_options_load(div){
    AddTextArea("div_process_commandline", "txt_commandline", 10);
    
    AddRow(div, [8, 4], undefined, "div_process_commandline_line");
    AddButton("div_process_commandline_line_1", "Download BOT", "btn_download_bot_click", "btn_download_bot", {"type" : "warning"})
        
    //EnviarJsonPost("/secanalysis/pages/panels/process_run.php", {"id" : Parameter("id"), "user" : USER._id}, function(data, erro, entrada, parametros) {
    //    txt_commandline.val(data.command);
    //});
    EdbRead("project", ["_id"], [Parameter("id")], function(projeto, erro, status){
        txt_commandline.val( "sudo python3 ./app.py -s '"+ (window.location.host.indexOf(":") >= 0 ? window.location.host.split(":")[0] : window.location.host) +"' -p '"+ projeto['_id'] +"' -t '"+ projeto['token'] +"' -u '"+ USER._id +"' -pt "+ window.location.protocol.replace(":", "") +" -po " + ( window.location.port != "" ? window.location.port : "80")  );
    });
}

function btn_nmap_salvar_click(){
    EdbWrite(["project_nmap_arguments"], [["_id", "project_id", "enable", "arguments"]], 
    [[Parameter("id"), Parameter("id"),rd_nmap_enable.val()[0] , txt_nmap_arguments.val() ]], function(saida, status, erro){
        alert("Sucesso");
    });
}


// ----------=========== NMAP DOMÍNIO ================-----------------
function nmap_dominio_options_load(div){
    var DEFAULT_NMAP_ARGUMENTS = "-sV";
    EdbRead("project_nmap_domain_arguments", ["project_id"], [Parameter("id")], function(saida, erro, status){
        AddRadioGroup(div, "rd_nmap_domain_enable", [{"value" : 0, "field" : "Não executar" }, {"value" : 1 , "field" : "Executar"}], "field", "value" , false);
        AddText(div, "text", "Argumentos", "Os argumentos NMAP, acesse livro Hacker entre a luz e as trevas", "txt_nmap_domain_arguments");
        AddRow(div, [8, 4], undefined, "div_nmap_domain_button");
        
        AddButton("div_nmap_domain_button_1", "Salvar parametros Nmap", "btn_nmap_dominio_salvar_click", "btn_nmap_domain_salvar", {"type" : "warning"})
        
        if(saida == undefined){
            EdbWrite(["project_nmap_domain_arguments"], [["_id", "project_id", "enable", "arguments"]], [[Parameter("id"), Parameter("id"), 0, DEFAULT_NMAP_ARGUMENTS]], function(saida, status, erro){
                rd_nmap_domain_enable.val(0);
                txt_nmap_domain_arguments.val(DEFAULT_NMAP_ARGUMENTS);
            });
            
        } else{
            rd_nmap_domain_enable.val(saida['enable']);
            txt_nmap_domain_arguments.val(saida['arguments']);
        }
    });
}

function btn_nmap_dominio_salvar_click(){
    EdbWrite(["project_nmap_domain_arguments"], [["_id", "project_id", "enable", "arguments"]], 
    [[Parameter("id"), Parameter("id"),rd_nmap_domain_enable.val()[0] , txt_nmap_domain_arguments.val() ]], function(saida, status, erro){
        alert("Sucesso");
    });
}

// ----------=========== DIVERSOS ================-----------------
function diversos_options_load(div){
    var DEFAULT_OTHER_ARGUMENTS = JSON.stringify({"dns" : 0, "whois" : 0});
    EdbRead("project_other_arguments", ["project_id"], [Parameter("id")], function(saida, erro, status){
        AddHeader(div, 5, "Busca por DNS")
        AddRadioGroup(div, "rd_dns_enable",   [{"value" : 0, "field" : "Não executar busca por DNS" }, {"value" : 1 , "field" : "Executar busca por DNS"}], "field", "value" , false);
        //AddBr("div_diversos", 2);
        AddHeader(div, 5, "Busca por Whois")
        AddRadioGroup(div, "rd_whois_enable", [{"value" : 0, "field" : "Não executar busca whois" }, {"value" : 1 , "field" : "Executar busca whois"}], "field", "value" , false);
        
        AddRow(div, [8, 4], undefined, "div_other_button");
        AddButton("div_other_button_1", "Salvar parametros Diversos", "btn_other_salvar_click", "btn_other_salvar", {"type" : "warning"})
        
        if(saida == undefined){
            EdbWrite(["project_other_arguments"], [["_id", "project_id", "enable", "arguments"]], [[Parameter("id"), Parameter("id"), 0, DEFAULT_OTHER_ARGUMENTS]], function(saida, status, erro){
                rd_dns_enable.val(0);
                rd_whois_enable.val(0);
            });
            
        } else{
            saida = JSON.parse(saida['arguments']);
            rd_dns_enable.val(saida['dns']);
            rd_whois_enable.val(saida['whois']);
        }
    });
}

function btn_other_salvar_click(){
    EdbWrite(["project_other_arguments"], [["_id", "project_id", "enable", "arguments"]], 
    [[Parameter("id"), Parameter("id"), 0 , JSON.stringify({"dns" :  rd_dns_enable.val(), "whois" : rd_whois_enable.val()}) ]], function(saida, status, erro){
        alert("Sucesso");
    });
}


// --------------------------------------------------------------
function main_process_run(){
    var div = $("#Process");
    AddTab(div, [{"text" : "Nmap (Ambiente Local)", "div" : "div_process_nmap"}, {"text" : "Nmap (Domínios)", "div" : "div_process_nmap_domain"},
                    {"text" : "Shodan", "div" : "div_process_shodan"}, {"text" : "Ipquality", "div" : "div_process_ipquality"}, {"text" : "Diversos", "div" : "div_diversos"},
                    {"text" : "Command line", "div" : "div_process_commandline"}, {"text" : "Execuções", "div" : "div_execution_commandline"}],
     "tab_process_run")

    nmap_options_load("div_process_nmap");
    nmap_dominio_options_load("div_process_nmap_domain");
    shodan_options_load("div_process_shodan");
    commandline_options_load("div_process_commandline");
    diversos_options_load("div_diversos");
    execucoes_load("div_execution_commandline");
    ipquality_options_load("div_process_ipquality");

    EnviarJsonPost("/secanalysis/pages/panels/report.php", {"id" : Parameter("id"), "user" : USER._id}, function(data, erro, entrada, parametros) {
        console.log(data);
        relatorio.html("<a class='button' href='"+ data.url +"' target='_blank'>Gerar o documento Penetration Test Report</a>");
    });
}



//