//require EnviarJsonPost layout v1
//require Parameter util v1
//require EdbRead edb v1
//require EdbWrite edb v1
//require AddJson layout v1
//require action/AddButton layout v1

var cve_banco = undefined;
function main(){
    EdbRead("cve", ["codigo"], [Parameter("cve")], function(saida, status, erro){
        cve_banco = saida;
        //delete saida["_id"];
        delete saida["_inserttime"];
        delete saida["_updatetime"];
        
        AddJson("raiz", "cve_detalhes", "CVE", undefined, saida);
        AddButton("raiz", "Adicionar na lista deste domínio", "btn_adicionar_cve_click", "btn_adicionar_cve");
    });

}

function btn_adicionar_cve_click(){
    domain_id = Parameter("domain_id", window.parent.location.href);

    EdbWrite("cve_occurrence", ["_id",  "cve_id", "occurrence_id"], 
        [  cve_banco._id + domain_id,  cve_banco._id, domain_id ], function(data, status, erro){
            if(status){
                alert("Adicionado com sucesso.");
            }
    });

}