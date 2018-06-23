Array.prototype.merge = function(...args) {
  return this.concat(...args).filter(function(o) {return o})
}
Array.prototype.diff = function(a) {
  return this.filter(function(o) {return a.indexOf(o) < 0})
}
