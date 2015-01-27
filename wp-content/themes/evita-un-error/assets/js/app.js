// Hola
jQuery(document).ready(function() {
  mistake = jQuery('.mistake');
  if (mistake.length == 0) {
    jQuery('section').append('<h2 style="margin-top:295px;">Aún no hay ninguna experiencia así.</h2><h3>Prueba a realizar una búsqueda diferente</h3>');
  }
  $url = jQuery('.url-mistake');
  $url.click(function(){
    window.location.href = jQuery(this).data('href');
  });

  creaError = jQuery(".page-id-55");
  if (creaError.length > 0) {
    if (jQuery('.frontier-list').length > 0) {
      jQuery('table').addClass('table table-striped');
      jQuery('#post-55').html(function(i, text) {
      return text.slice(0, -54);
      });
      jQuery('h1.title').text('Tus artículos');
      jQuery('.frontier-menu').hide();
    }
    if (jQuery('.frontier_post_form').length > 0) {
      jQuery('select').addClass('form-control');
      jQuery('tr:last').addClass('last');
      jQuery('.last button')
    }
    jQuery('a:contains("Crear nuevo artículo")').addClass('btn purple').text('Añade un error').addClass('hidden');
    jQuery('table').addClass('table');
  }
});