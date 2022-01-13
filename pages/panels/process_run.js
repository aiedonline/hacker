

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

function commandline_options_load(div){
    AddTextArea("div_process_commandline", "txt_commandline", 10);
    EnviarJsonPost("/secanalysis/pages/panels/process_run.php", {"id" : Parameter("id"), "user" : USER._id}, function(data, erro, entrada, parametros) {
        txt_commandline.val(data.command);
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

// --------------------------------------------------------------
function main_process_run(){
    var div = $("#Process");
    AddTab(div, [{"text" : "Nmap (Ambiente Local)", "div" : "div_process_nmap"}, {"text" : "Nmap (Domínios)", "div" : "div_process_nmap_domain"},
                    {"text" : "Shodan", "div" : "div_process_shodan"},
                    {"text" : "Command line", "div" : "div_process_commandline"}],
     "tab_process_run")

    nmap_options_load("div_process_nmap");
    nmap_dominio_options_load("div_process_nmap_domain");
    shodan_options_load("div_process_shodan");
    commandline_options_load("div_process_commandline");
}