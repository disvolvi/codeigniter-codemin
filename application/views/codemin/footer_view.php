
	</div>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="./public/codemin/ckeditor/ckeditor.js"></script>
	<script src="./public/codemin/funcoes.js"></script>
	<script src="./public/codemin/js/jquery.meiomask.js"></script>
	<script src="./public/codemin/js/bootstrap.min.js"></script>

	<!-- JQUERY UPLOADER -->
	<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-upload fade">
	        <td>
	            <span class="preview"></span>
	        </td>
	        <td>
	            <p class="name">{%=file.name%}</p>
	            {% if (file.error) { %}
	                <div><span class="label label-important">Erro</span> {%=file.error%}</div>
	            {% } %}
	        </td>
	        <td>
	            <p class="size">{%=o.formatFileSize(file.size)%}</p>
	            {% if (!o.files.error) { %}
	                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
	            {% } %}
	        </td>
	        <td>
	            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
	                <button class="btn btn-primary start">
	                    <i class="icon-upload icon-white"></i>
	                    <span>Iniciar</span>
	                </button>
	            {% } %}
	            {% if (!i) { %}
	                <button class="btn btn-warning cancel">
	                    <i class="icon-ban-circle icon-white"></i>
	                    <span>Cancelar</span>
	                </button>
	            {% } %}
	        </td>
	    </tr>
	{% } %}
	</script>
	<!-- The template to display files available for download -->
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-download fade">
	        <td>
	            <span class="preview">
	                {% if (file.thumbnailUrl) { %}
	                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
	                {% } %}
	            </span>
	        </td>
	        <td>
	            <p class="name">
	                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
	            </p>
	            {% if (file.error) { %}
	                <div><span class="label label-important">Erro</span> {%=file.error%}</div>
	            {% } %}
	        </td>
	        <td>
	            <span class="size">{%=o.formatFileSize(file.size)%}</span>
	        </td>
	        <td>
	            <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
	                <i class="icon-trash icon-white"></i>
	                <span>Deletar</span>
	            </button>
	            <input type="checkbox" name="delete" value="1" class="toggle"><br/><br/>
	            <button class="btn btn-primary btn-mini copiar-url-imagem">Copiar URL<input type="hidden" value="{%=file.url%}" /></button>
	        </td>
	    </tr>
	{% } %}
	</script>
	<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
	<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
	<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
	<script src="./public/codemin/jquery.upload/js/vendor/jquery.ui.widget.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.iframe-transport.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.fileupload.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.fileupload-process.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.fileupload-image.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.fileupload-audio.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.fileupload-video.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.fileupload-validate.js"></script>
	<script src="./public/codemin/jquery.upload/js/jquery.fileupload-ui.js"></script>
	<script src="./public/codemin/jquery.tablednd.js"></script>
	
	<script src="./public/codemin/jquery.upload/js/main.js?id=teste"></script>
	<!-- JQUERY UPLOADER -->

	<?php foreach ($scriptFooter as $script) {
		echo $script;
	} ?>

	<?php if($nivel_acesso == 3){ ?>
	<script>
		$(document).ready(function(){
			$("#registros").tableDnD({
				onDragClass: "info",
				onDrop: function(table, row) {

					$("#ordenar-carregando").show();
					$("#ordenar-sucesso").hide();
					$("#ordenar-erro").hide();

        	$.ajax({
						type: "POST",
      			dataType: "json",
						data: $.tableDnD.serialize(),
						url: '<?= base_url() ?>opcoes/ordenar/<?= $this->uri->segment(2) ?>'
					}).done(function(result) {

						$("#ordenar-carregando").hide();

						if(result.sucesso){
							$("#ordenar-sucesso").show();
						} else if(result.erro) {
							$("#ordenar-erro").show();
						}
					});

    		}
			});

			$('form').on('click','.copiar-url-imagem',function(e){
				e.preventDefault();
				var url = $(this).children('input:hidden').val().replace("<?=base_url()?>", "./");
				window.prompt('Url da imagem', url);
			});

		});
	</script>
	<?php } ?>

	<script>
		$(document).ready(function(){

			// Abre a caixa de busca
			$('table thead tr th button.btn-busca').click(function(){
				$('table thead tr th button.btn-cancelar').click();
				$(this).hide();
				$(this).parent().children('input').show().focus();
				$(this).parent().children('button.btn-cancelar').show();
			});

			// Fecha a caixa de busca
			$('table thead tr th button.btn-cancelar').click(function(){
				$(this).hide();
				$('table tbody tr').show();
				$(this).parent().children('input').hide().val('');
				$(this).parent().children('button.btn-busca').show();
			});

			// Faz a busca
			$('table thead tr th input.input-busca').keyup(function(){
				var index = $(this).parent().index() + 1;
				var busca = $(this).val();
				$('table tbody tr').hide();
				$('table tbody tr td:contains("' + busca + '"):nth-child(' + index + ')').parent().show();
			});
			
		});
	</script>
	
</body>
</html>