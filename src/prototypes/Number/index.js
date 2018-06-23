Number.prototype.format = function() {
  return this.toFixed(9)
}
Number.prototype.percent = function(n) {
  if (n) return '' + this.toFixed(9) + '%'
  return '' + this + '%'
}
