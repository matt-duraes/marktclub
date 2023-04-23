// @template "site"
// @system "Loading"
// @system "Funcao"
// @system "Alerta"
// @system "Form"
// @system "Pagina"


window.addEventListener('load', () => {
	const LINK = document.querySelector('#LINK').value;
});

let googlemap = {
	mapa:null,
	markerClusterer:null,
	markers:[]
};

let usuario = {
	latitude: -23.5489,
	longitude: -46.6388,
	endereco: null
};

let parceiro = {
	lista:null
};

let geocodeRequest, geocodeValorAtual, geocodeDigitando = false; geocodeBuscando = false; geocodeBuscarAutomoticamente = false, parceiroBuscando = false;

googlemap.reiniciar = function(){

	usuario.latitude = -23.5489;
	usuario.longitude = -46.6388;

	window.addEventListener('load', googlemap.iniciar({
		scroll:true
	}));

};

let pegarRaio = function(){
	
	var raio;
	if (window.matchMedia("(max-width: 700px)").matches) {
		raio = document.querySelector('#form_buscar_mobile input[name=raio]') ? document.querySelector('#form_buscar_mobile input[name=raio]').value : null;
	} else {
		raio = document.querySelector('#form_buscar input[name=raio]').value;
	}
	raio = document.querySelector('#form_buscar input[name=raio]').value;
	
	if(raio == 2){
		return {
			radius: 2000,
			raio: 2
		};
	}else if(raio == 5){
		return {
			radius: 5000,
			raio: 5
		};
	}else if(raio == 10){
		return {
			radius: 10000,
			raio: 10
		};
	}else if(raio == 30){
		return {
			radius: 30000,
			raio: 30
		};
	}else if(raio == 50){
		return {
			radius: 50000,
			raio: 50
		};
	}

	return {
		radius: 30000,
		raio: 30
	};

};

googlemap.mostrarPontoNoMapa = function(){

	if(!parceiro.lista){
		setarUsuarioNoMapa();
		return false;
	}

	googlemap.markers = [];


	let quantidade = parceiro.lista.length;
	let i, cliqueNoPonto, parceiroPosicao, parceiroIcone, marker;

	// let latlngbounds = new google.maps.LatLngBounds();

	for(i = 0; i < quantidade; ++i){

		parceiroPosicao = new google.maps.LatLng(parceiro.lista[i].geolocalizacao.latitude, parceiro.lista[i].geolocalizacao.longitude);
		parceiroIcone = new google.maps.MarkerImage('https://clube.marktclub.com.br/images/mapa_icone.png', new google.maps.Size(24, 32));

		marker = new google.maps.Marker({
			'position': parceiroPosicao,
			'icon': parceiroIcone
		});

		mouseoverNoPonto = googlemap.mouseoverNoPonto(parceiro.lista[i]);
		mouseoutNoPonto = googlemap.mouseoutNoPonto(parceiro.lista[i]);
		cliqueNoPonto = googlemap.cliqueNoPonto(parceiro.lista[i]);

		google.maps.event.addListener(marker, 'mouseover', mouseoverNoPonto);
		google.maps.event.addListener(marker, 'mouseout', mouseoutNoPonto);
		google.maps.event.addListener(marker, 'click', cliqueNoPonto);

		googlemap.markers.push(marker);
		// latlngbounds.extend(marker.position);

	}
	googlemap.markerClusterer = new MarkerClusterer(googlemap.mapa, googlemap.markers, {imagePath: 'https://clube.marktclub.com.br/images/mapa_mais_'});
	// googlemap.mapa.fitBounds(latlngbounds);

	setarUsuarioNoMapa();

};

googlemap.iniciar = function(option) {
	let latlng = new google.maps.LatLng(usuario.latitude, usuario.longitude);

	let options = {
		scrollwheel: option.scroll || false,
		zoom: 15,
		center: latlng,
		disableDefaultUI: true,
		panControl: false,
		zoomControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	googlemap.mapa = new google.maps.Map(document.getElementById("mapa"), options);
	googlemap.mapa.addListener("dragend", function() {
		let centroDoMapa = this.getCenter();
		usuario.latitude = centroDoMapa.lat();
		usuario.longitude = centroDoMapa.lng();

		let botao = document.querySelector(".botao_atualizar_mapa");
		if (!botao.classList.contains("atualizar")) {
			botao.classList.add("atualizar");
		}
	});

	googlemap.mostrarPontoNoMapa();
};

let setarUsuarioNoMapa = function(){

	let usuarioPosicao = new google.maps.LatLng(usuario.latitude, usuario.longitude);
	let ususarioIcone = new google.maps.MarkerImage('https://clube.marktclub.com.br/images/mapa_usuario.png', new google.maps.Size(32, 32));

	let setarPosicao = new google.maps.Marker({
		'position': usuarioPosicao,
		'icon': ususarioIcone
	});

	setarPosicao.setMap(googlemap.mapa);

	if(parceiro.lista){
		
		let circuloDoRaio = new google.maps.Circle({
			strokeColor: '#FFFFFF',
			strokeOpacity: 0.5,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0,
			map: googlemap.mapa,
			center: {lat: usuario.latitude, lng: usuario.longitude},
			radius: pegarRaio().radius
		});

		googlemap.mapa.fitBounds(circuloDoRaio.getBounds());
	}

};

googlemap.cliqueNoPonto = function(dado) {

	return function(e) {
		document.querySelector('.parceiro_titulo').innerText = dado.titulo;
		document.querySelector('.parceiro_imagem').style.backgroundImage = url(dado.imagem.logo);
		document.querySelector('.parceiro_texto').innerText = dado.descricao;
		document.querySelector('.parceiro_desconto').innerText = dado.desconto;
		document.querySelector('.parceiro_link').setAttribute('href', document.querySelector('#LINK').value+'/loja/'+dado.url.valor);
		document.querySelector('.parceiro_rota').setAttribute('href', 'https://www.google.com.br/maps/dir//'+dado.geolocalizacao.latitude+', '+dado.geolocalizacao.longitude);
		document.querySelector('.parceiro_rota').style.display = 'flex';

		let blocoParceiro = document.querySelector('#bloco_parceiro');
		blocoParceiro.style.display = 'flex';
		blocoParceiro.style.opacity = 0;
		blocoParceiro.style.bottom = '-100%';
		setTimeout(() => {
			blocoParceiro.style.opacity = 1;
			blocoParceiro.style.bottom = 0;
		}, 500);

	};
};

googlemap.mouseoverNoPonto = function(dado) {
	return function(e) {

		if(e.wa.screenX == undefined || e.wa.offsetX == undefined || e.wa.screenY == undefined || e.wa.offsetY == undefined){
			return false;
		}
		
		let esquerda = e.wa.screenX - e.wa.offsetX;
		let topo = (e.wa.screenY - e.wa.offsetY) - 42;

		document.querySelector('#bloco_previa h1').innerText = dado.titulo;
		document.querySelector('#bloco_previa').classList.add('ativo');
		document.querySelector('#bloco_previa').style.top = topo;
		document.querySelector('#bloco_previa').style.left = esquerda - (parseFloat(document.querySelector('#bloco_previa').clientWidth) / 2) + 5;


	};
};
googlemap.mouseoutNoPonto = function(dado) {
	return function(e) {
		document.querySelector('#bloco_previa').classList.remove("ativo");
		document.querySelector('#bloco_previa h1').innerText = '';
	};
};

function pegarLocalizacaoAtual(){

	if(navigator.geolocation){
		return navigator.geolocation.getCurrentPosition(setarPosicaoInicial, erroPegarLocalizacao);
	}else {
		erroSuporte();
	}
};
pegarLocalizacaoAtual();

var erroSuporte = function(){
	Alerta.mensagem('Sem suporte', 'Seu navegador não ter suporte, esse problema pode ser solucionado usando um navegadores como o Google Chrome, Firefox, Edge o Safari nas versões mais recentes (Mesmo usando um dos navegadores indicados mas em versões antigas, não podemos garantir que o mesmo tenha suporte para esse procedimento', false);
};

function setarPosicaoInicial(position){

	usuario.latitude = position.coords.latitude;
	usuario.longitude = position.coords.longitude;

	salvarLocalizacaoUsuario();
	buscarParceiro();

}

let salvarLocalizacaoUsuario = function(){

};

let buscarParceiro = async () => {


	Loading.show();

	if(geocodeBuscando == true){

		geocodeBuscarAutomoticamente = true;
		return false;

	}else if(usuario.latitude == null || usuario.longitude == null){

		return false;

	}

	parceiroBuscando = true;
	document.querySelector('#form_buscar ul').style.display = "none";  
	document.querySelector('#form_buscar ul li').innerText = ''; 
	document.querySelector('#form_buscar_mobile ul') ? document.querySelector('#form_buscar_mobile ul').style.display = "none" : null;  
	document.querySelector('#form_buscar_mobile ul li') ? document.querySelector('#form_buscar_mobile ul li').innerText = '' : null;  
	/* document.querySelector('.erro_mapa').style.display = "none";   */

	let pesquisa = document.querySelector('#form_buscar input[name=pesquisa]').value;
	let categoria = document.querySelector('#form_buscar input[name=categoria]').value;
	let pesquisaMobile = document.querySelector('#form_buscar_mobile input[name=pesquisa]') ? document.querySelector('#form_buscar_mobile input[name=pesquisa]').value : null;
	let categoriaMobile = document.querySelector('#form_buscar_mobile input[name=categoria]') ? document.querySelector('#form_buscar_mobile input[name=categoria]').value : null; 

	const body = new FormData();
	body.append('pesquisa',  window.matchMedia("(max-width: 700px)").matches ? pesquisaMobile : pesquisa);
	body.append('categoria', window.matchMedia("(max-width: 700px)").matches ? categoriaMobile : categoria);
	body.append('latitude', usuario.latitude);
	body.append('longitude', usuario.longitude);
	body.append('raio', pegarRaio().raio);

	const response = await fetch(document.querySelector('#LINK').value+'/loja/mapa-listar', {
		method: 'POST',
		body
	});

	let json;
    try {
        json = await response.json();

		parceiro.lista = resposta;

		window.addEventListener('load', googlemap.iniciar({
			scroll:true
		}));
	/* 	document.querySelector('.modal_busca_mapa').style.display = "none";   */

		Loading.hide();
    } catch (error) {
        json = {};

	/* 	document.querySelector('.modal_busca_mapa').style.display = "none";   */

		googlemap.reiniciar();
		Loading.hide();
    }

};

let erroLocalizacao = function(){

};
let erroPermissao = function(){
	Alerta.mensagem('Erro de permissão', 'Você bloqueou o compartilhamento da sua localização, compartilhe sua localização para continuar.<br>Geralmente essa opção fica no canto superior esquerdo do navegador.', false);
};
let erroIndisponivel = function(){
	Alerta.mensagem('Erro', 'Erro ao adquirir sua localização, atualize e tente novamente.', false);

};
let erroOutro = function(){
	Alerta.mensagem('Erro', 'Erro ao buscar sua localização, atualize e tente novamente.', false);

};

function erroPegarLocalizacao(error){
	
	Loading.hide();

	switch(error.code){
		case error.PERMISSION_DENIED:
			erroPermissao();
		break;
		case error.POSITION_UNAVAILABLE:
			erroIndisponivel();
		break;
		case error.TIMEOUT:
			erroOutro();
		break;
		case error.UNKNOWN_ERROR:
			erroOutro();
		break;
	}

};


/*
|--------------------------------------------------------------------------
| ACOES DOS BOTOES DA PAGINA
|--------------------------------------------------------------------------
*/

let botaoRaio = document.querySelector('.botao_raio');
botaoRaio.addEventListener('click', () => {
	document.querySelector('.botao_raio ul').toggle();
});

let botaoRaioLista = document.querySelector('.botao_raio');
botaoRaioLista.addEventListener('click', () => {
	document.querySelector('.botao_raio ul').toggle();
	let valor = this.setAttribute('data-valor');
	let texto = this.setAttribute('data-texto');

	document.querySelector('#bloco_raio p span').innerText = texto;
	document.querySelector('#form_buscar input[name=raio]').value = valor;

	document.querySelector('#bloco_raio ul').style.display = '';
	buscarParceiro();
});

let blocoParceiroVoltar = document.querySelector('#bloco_parceiro .voltar, #bloco_parceiro .fechar');
if(blocoParceiroVoltar){
	blocoParceiroVoltar.addEventListener('click', function() {
		const blocoParceiro = document.querySelector('#bloco_parceiro');
		blocoParceiro.style.transition = 'all 500ms';
		blocoParceiro.style.opacity = 0;
		blocoParceiro.style.bottom = '-100%';

		setTimeout(function() {
			document.querySelector('.parceiro_titulo').textContent = '';
			document.querySelector('.parceiro_imagem').style.backgroundImage = '';
			document.querySelector('.parceiro_texto').textContent = '';
			document.querySelector('.parceiro_desconto').textContent = '';
			document.querySelector('.parceiro_link').setAttribute('href', '');
			document.querySelector('.parceiro_rota').setAttribute('href', '');
			blocoParceiro.style.display = 'none';
		}, 500);
	});

}

// Buscar por localização atual
document.querySelector('#botao_buscar_por_localizacao').addEventListener('click', function() {
	pegarLocalizacaoAtual();
	usuario.endereco = null;
	document.querySelector('#form_buscar input[name=local]').value = '';
	document.querySelector('#form_buscar_mobile input[name=local]') ? document.querySelector('#form_buscar_mobile input[name=local]').value = '' : null;
});


// Buscar por cidade ou local
document.querySelector('#atualizar_mapa').addEventListener('click', function(e) {
	e.preventDefault();
	buscarParceiro();
});


document.querySelector("#form_buscar input[name=local]").addEventListener('keydown', function(e) {
	handleKeydown();
});


const formBuscar = document.querySelector("#form_buscar");
const formBuscarMobile = document.querySelector("#form_buscar_mobile") ? document.querySelector("#form_buscar_mobile") : null ;

if (formBuscar) {
	formBuscar.querySelectorAll("ul li").forEach(li => {
		li.addEventListener("click", () => {
			formBuscar.querySelectorAll("ul").forEach(ul => {
				ul.style.display = "none";
			});
			formBuscar.querySelectorAll("ul li").forEach(li => {
				li.textContent = "";
			});
			formBuscar.querySelector("input[name=local]").value = usuario.endereco;
			geocodeValorAtual = usuario.endereco;

			buscarParceiro();
		});
	});
}

if (formBuscarMobile) {
	formBuscarMobile.querySelectorAll("ul li").forEach(li => {
		li.addEventListener("click", () => {
			formBuscarMobile.querySelectorAll("ul").forEach(ul => {
				ul.style.display = "none";
			});
			formBuscarMobile.querySelectorAll("ul li").forEach(li => {
				li.textContent = "";
			});
			formBuscarMobile.querySelector("input[name=local]").value = usuario.endereco;
			geocodeValorAtual = usuario.endereco;
		});
	});
}

document.querySelector("#form_buscar input[name=local]").addEventListener("keyup", function() {
	if (parceiroBuscando === true) {
		return false;
	}

	let valor = document.querySelector("#form_buscar input[name=local]").value;
	if (valor === geocodeValorAtual) {
		return false;
	} else if (valor === "") {
		document.querySelector("#form_buscar ul").style.display = "none";
		document.querySelectorAll("#form_buscar ul li").forEach(li => li.textContent = "");
		return false;
	}
	geocodeValorAtual = valor;

	geocodeBuscando = true;

	usuario.latitude = null;
	usuario.longitude = null;
	usuario.endereco = null;

	document.querySelector("#form_buscar ul").style.display = "block";
	document.querySelectorAll("#form_buscar ul li").forEach(li => li.textContent = "Buscando...");

	clearTimeout(geocodeDigitando);

	geocodeDigitando = setTimeout(() => {
		buscarEnderecoViaGeocode(document.querySelector("#form_buscar"));
	}, 500);
});

let buscarEnderecoViaGeocode = async (formulario) => {
	let form = formulario;
	let valor = form.querySelector('input[name=local]').value;

	if (geocodeRequest) {
		geocodeRequest.abort();
	}

	if (valor === '') {
		form.querySelector('ul').style.display = 'none';
		return false;
	}

	const body = new FormData();
	body.append('pesquisa', valor);

	const response = await fetch(LINK+'/api/geocode', {
		method: 'GET',
		body,
	});

	let json;
    try {
        json = await response.json();
		if (!resposta.erro) {
			usuario.endereco = resposta.endereco;
			usuario.latitude = resposta.latitude;
			usuario.longitude = resposta.longitude;

			form.querySelector('ul li').textContent = usuario.endereco;

			if (geocodeBuscarAutomoticamente) {
				geocodeBuscarAutomoticamente = false;
				geocodeBuscando = false;
				buscarParceiro();
				form.querySelector('input[name=local]').value = resposta.endereco;
				geocodeValorAtual = resposta.endereco;
			}
		}else if (resposta.erro) {
			form.querySelector('ul').style.display = 'none';
			form.querySelector('ul li').textContent = '';
		}
    } catch (error) {
		form.querySelector('ul li').textContent = 'Sem resultados, tente refazer sua busca.';
		usuario.endereco = null;
		usuario.latitude = null;
		usuario.longitude = null;
		if (geocodeBuscarAutomoticamente) {
			geocodeBuscarAutomoticamente = false;
		}
    }

};


/*
|--------------------------------------------------------------------------
| MODAL
|--------------------------------------------------------------------------
*/

const carregarFuncoesMapa = () => {
    const botaoFechar = document.querySelector('.botao_fechar_popup');

	botaoFechar.addEventListener('click', () => {
		Pagina.staticFechar();
	});


	document.querySelector('#form_buscar_mobile button').addEventListener('click', function(e) {
		e.preventDefault();

		document.querySelector("#form_buscar_mobile input[name=local]").blur();
		buscarParceiro();
	});

	document.querySelector("#form_buscar_mobile input[name=local]").addEventListener('keydown', function(e) {
		handleKeydown();
	});
};

const PaginaBuscaMapa = new Pagina('Busca', document.querySelector('#LINK').value + '/loja/mapa-modal', {}, true, true, carregarFuncoesMapa);
const botaoPopup = document.querySelector('.buscar_popup');
botaoPopup.addEventListener('click', () => {
	PaginaBuscaMapa.abrir();
});

const handleKeydown = (e) => {
	const form = e.target.closest('form');
	const valor = e.target.value;
	const endereco = usuario.endereco;
	const tecla = e.keyCode;

	if (tecla === 27) {
		clearFormDisplay(form);
		formBuscaLimpar('');
		e.target.value = '';
		return false;

	} else if ((tecla === 9 || tecla === 13) && valor !== '' && endereco !== '' && geocodeBuscando === false) {
		e.target.value = endereco;
		geocodeValorAtual = endereco;
		clearFormListItems(form);
		clearFormDisplay(form);
	}

	document.querySelector("#form_buscar input[name=local]").addEventListener('keydown', handleKeydown);
	document.querySelector("#form_buscar_mobile input[name=local]").addEventListener('keydown', handleKeydown);

	const clearFormDisplay = (form) => {
		form.querySelectorAll('ul').forEach((ul) => {
			ul.style.display = 'none';
		});
	};

	const clearFormListItems = (form) => {
		form.querySelectorAll('ul li').forEach((li) => {
			li.textContent = '';
		});
	};
};

window.addEventListener('load', googlemap.iniciar({
	scroll:true
}));


