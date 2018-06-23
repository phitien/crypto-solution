var dispatchUserSignedInEvent = function() {
  dispatchEvent(new CustomEvent('UserSignedIn', {
    detail: Array.from(arguments)
  }))
}
var dispatchUserSignedOutEvent = function() {
  dispatchEvent(new CustomEvent('UserSignedOut', {
    detail: Array.from(arguments)
  }))
}
var dispatchUserSignedUpEvent = function() {
  dispatchEvent(new CustomEvent('UserSignedUp', {
    detail: Array.from(arguments)
  }))
}
