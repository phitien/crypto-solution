String.prototype.lcfirst = function() {
  return this.substr(0, 1).toLowerCase() + this.substr(1)
}
String.prototype.toCamel = function() {
    var str = this.replace(/^([A-Z])|\s([a-z])/g, function(match, p1, p2, offset) {
        if (p2) return ` ${p2.toUpperCase()}`
        return p1.toLowerCase()
    })
    return str
}
String.prototype.ucfirst = function() {
  return this.substr(0, 1).toUpperCase() + this.substr(1)
}
