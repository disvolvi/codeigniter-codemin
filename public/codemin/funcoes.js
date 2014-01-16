$(document).ready(function(){
	$('a.confirmar-excluir').click(function(){
		if(!confirm('Deseja realmente excluir?')){
			return false;
		}
	});
	$('input[type="text"]').setMask();
});
function opcoes(base_url,campo,tabela,tipo){

	// Adicionar Opção
	$("#modal-" + campo + " > .modal-body > .controls-row > input:button").click(function(){

		var botao = $(this);
		botao.attr("disabled", "disabled");
		var opcao = botao.parent().children("input:text").val();

		$.ajax({
			type: "POST",
			data: "tabela=" + tabela + "&campo=" + campo + "&opcao=" + opcao + "&tipo=" + tipo,
			url: base_url + 'adicionar'
		}).done(function(result) {
			var resultado = jQuery.parseJSON(result);
			if(tipo == 'select'){
				$('select[name="' + campo + '"]').append('<option value="' + resultado.id + '">' + resultado.valor + '</option>');
			} else if(tipo == 'checkbox'){
				$('#label_' + campo).append('<label class="checkbox"><input type="checkbox" name="' + campo + '[]" value="' + resultado.id + '">' + resultado.valor + '</label>');
			} else if(tipo == 'radio'){
				$('#label_' + campo).append('<label class="radio"><input type="radio" name="' + campo + '[]" value="' + resultado.id + '">' + resultado.valor + '</label>');
			}
			botao.parent().parent().children('table').children('tbody')
			.append('<tr><td>' + opcao + '</td><td><input type="button" value="Remover" class="btn btn-danger"/><input type="hidden" value="' + resultado.id + '"/>');
			botao.removeAttr("disabled");
			botao.parent().children("input:text").val('');
		});

	});

	// Remover Opção
	$(document).on("click", "#modal-" + campo + " > .modal-body > table > tbody > tr > td > input:button", function(){

		var linha = $(this).parent().parent();
		var id = linha.find('input:hidden').val();

		$.ajax({
			type: "POST",
			data: "id=" + id,
			url: base_url + 'remover'
		}).done(function(result) {
			var resultado = jQuery.parseJSON(result);
			if(resultado.success == true){
				if(tipo == 'select'){
					$('select[name="' + campo + '"] option[value=' + id + ']').remove();
				} else if(tipo == 'checkbox' || tipo == 'radio'){
					$('#label_' + campo + ' input[value=' + id + ']').parent().remove();
				}
				linha.remove();
			}
		});

	});
}

function google_maps(id,lat,lng){
	$(window).load(function(){
			initialize(id, lat, lng, 16);
	});
}
var map;

function initialize(id, latitude, longitude, zoom) {
  var latlng = new google.maps.LatLng(latitude, longitude);
  var myOptions = {
    zoom: zoom,
    center: latlng,
    scrollwheel: false,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('google-maps-' + id), myOptions);
  geocoder = new google.maps.Geocoder();

  addMarker(id, latitude, longitude);

}

function addMarker(id, latitude, longitude) {

  marker = new google.maps.Marker({
      position: new google.maps.LatLng(latitude,longitude),
      map: map,
      draggable: true
  });

  google.maps.event.addListener(marker, "dragend", function() {
  	
    document.getElementById('input-google-maps-' + id).value = marker.getPosition().lat() + ', ' + marker.getPosition().lng();

  });

}