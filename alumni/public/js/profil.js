//PASSIONS / ECOLE / ENTREPRISE
$(document).ready(function () {
    var wrapper = $('.input_fields_wrap') // Emplacement
  
    $(wrapper).on('click', '.remove_field', function (e) {
      // On gère la suppression
      e.preventDefault()
      $(this).parent('div').remove()
      x--
    })
  })