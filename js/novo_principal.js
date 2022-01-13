function txt_titulo_change(elemento){
	if(carregado == false) return;
	var texto = elemento.val();
	if(texto == projeto.titulo) return;
	var querys = [Query_prj_Projeto({"_id" : projeto._id, "titulo" : texto})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			projeto.titulo = texto;
			NotifySave(undefined, true);
		}else{
			NotifyError(error);
		}
	});
}

//plano_escolar_id

function cmb_unidade_salvar(plano_escolar_id){
	if(carregado == false) return;
	var querys = [Query_prj_Projeto({"_id" : projeto._id, "plano_escolar_id" : plano_escolar_id})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
		}else{
			NotifyError(error);
		}
	});
}

function cmb_tipo_gratificacao_onChangeDropDown(key, text, element, li_id){
	if(carregado == false) return;
	onChangeDropDown(key, text, element, li_id);
	var querys = [Query_prj_Projeto({"_id" : projeto._id, "tipo_gratificacao" : key})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
		}else{
			NotifyError(error);
		}
	});
}

function cmb_projeto_onChangeDropDown(key, text, element, li_id){
	if(carregado == false) return;
	onChangeDropDown(key, text, element, li_id);
	var querys = [Query_prj_Projeto({"_id" : projeto._id, "projeto_anterior_id" : key})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
		}else{
			NotifyError(error);
		}
	});
}



function txt_inicio_change(data){
	if(carregado == false) return;
	if(projeto.data_inicio == data) return;
	
	var querys = [Query_prj_Projeto({"_id" : projeto._id, "data_inicio" : moment(data).format("YYYY-MM-DD")})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
		}else{
			NotifyError(error);
		}
	});
}

function txt_fim_change(data){
	if(carregado == false) return;
	if(projeto.data_fim == data) return;
	
	var querys = [Query_prj_Projeto({"_id" : projeto._id, "data_fim" : moment(data).format("YYYY-MM-DD")})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
		}else{
			NotifyError(error);
		}
	});
}


function ck_hae_sim_click(){
	if(carregado == false) return;

	var querys = [Query_prj_Projeto({"_id" : projeto._id, "hae" : 1})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
			ck_hae_nao.val(!ck_hae_sim.val());
			txt_total_projeto.readOnly(!ck_hae_sim.val());
			//calcularSubstituicoes();
		}else{
			NotifyError(error);
		}
	});
}


function ck_hae_nao_click(){
	if(carregado == false) return;

	var querys = [Query_prj_Projeto({"_id" : projeto._id, "hae" : 0, "horas" : "0"})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
			ck_hae_sim.val(!ck_hae_nao.val());
			txt_total_projeto.val("0");
			txt_total_projeto.readOnly(!ck_hae_sim.val());
			//calcularSubstituicoes();
		}else{
			NotifyError(error);
		}
	});
}
//
function txt_total_projeto_change(){
	if(carregado == false) return;

	var querys = [Query_prj_Projeto({"_id" : projeto._id, "horas" : txt_total_projeto.val()})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
			//calcularSubstituicoes();
		}else{
			NotifyError(error);
		}
	});

}

function txt_total_outros_change(){
	if(carregado == false) return;

	var querys = [Query_prj_Projeto({"_id" : projeto._id, "horas_outros" : txt_total_outros.val()})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			NotifySave(undefined, true);
			//calcularSubstituicoes();
		}else{
			NotifyError(error);
		}
	});

}

function txt_resumo_change(elemento){
	if(carregado == false) return;
	if(projeto.resumo == txt_resumo.val()) return;
	var querys = [Query_prj_Projeto({"_id" : projeto._id, "resumo" : txt_resumo.val()})];
	NotifySave(undefined);
	DataSaveJson(querys, function(data, error){
		if(error == undefined){
			projeto.resumo = txt_resumo.val();
			NotifySave(undefined, true);
		}else{
			NotifyError(error);
		}
	});
}



