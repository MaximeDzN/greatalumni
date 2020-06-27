const MAX_FIELDS = 10 //Nombre maximum de passions
$(document).ready(function () {
  var wrapper = $('.input_fields_wrap') //Emplacement
  var add_button = $('.add_field_button') //Bouton d'ajout

  var x = 1 //Nombre initial

  $(add_button).click(function (e) {
    //Lorqu'on clique sur add_button
    e.preventDefault()
    if (x < MAX_FIELDS) {
      //On vérifie le nombre de passions
      $(wrapper).append(
        '<div><input type="text" name="hobbies[]" placeholder="Passion"/><a href="#" class="remove_field">Supprimer</a></div>',
      )
      x++
    }
  })

  $(wrapper).on('click', '.remove_field', function (e) {
    //L'utilisateur retire un champs

    e.preventDefault()
    $(this).parent('div').remove()
    x--
  })
})

$(document).ready(function () {
  var wrapper = $('.input_fields_wrap2')
  var add_button = $('.add_field_button2')

  var x = 1

  $(add_button).click(function (e) {
    e.preventDefault()
    if (x < MAX_FIELDS) {
      $(wrapper).append(
        '<div><input type="text" name="school_curriculum[]" placeholder="Ecole ou formation"/><a href="#" class="remove_field">Supprimer</a></div>',
      )
      x++
    }
  })

  $(wrapper).on('click', '.remove_field', function (e) {
    e.preventDefault()
    $(this).parent('div').remove()
    x--
  })
})

$(document).ready(function () {
  var wrapper = $('.input_fields_wrap1')
  var add_button = $('.add_field_button1')

  var x = 1

  $(add_button).click(function (e) {
    e.preventDefault()
    if (x < MAX_FIELDS) {
      $(wrapper).append(
        '<div><input type="text" name="career[]" placeholder="Entreprises ou un Stage"/><a href="#" class="remove_field">Supprimer</a></div>',
      )
      x++
    }
  })

  $(wrapper).on('click', '.remove_field', function (e) {
    e.preventDefault()
    $(this).parent('div').remove()
    x--
  })
})
