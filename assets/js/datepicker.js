// parameter file for datapicker
// do not forget to declare in twig file using datapicker :
// <input type="hidden" id="language" data-lang = "{{ app.request.locale }}"/>

require('bootstrap-datepicker/js/bootstrap-datepicker')
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.fr')
require('bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')
$(function () {
  const lang = document.getElementById('language').dataset.lang
  let formatDate = 'mm-dd-yyyy'
  if (lang === 'fr') {
    formatDate = 'dd-mm-yyyy'
  }
  $('.input-daterange input').each(function () {
    $(this).datepicker({
      format: 'dd/mm/YYYY'
    })
  })
})
