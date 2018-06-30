jQuery(document).ready(function() {
  jQuery('input.checkbox').on('click', function() {
    var cb = jQuery(this)
    cb.closest('div').toggleClass('checked')
  })
})
