Date.prototype.format = function(f) {
  if (!jQuery) return this.toLocaleDateString()
  if (!f && (!config || !config.dateformat)) return this.toLocaleDateString()
  if (!f) f = config.dateformat
  return jQuery.datepicker.formatDate(f, this)
}
