function firebaseUserNormalizer(user) {
  user.token = user.refreshToken
  return user
}
firebase.initializeApp(config.firebase)
firebase.auth().onAuthStateChanged(function(user) {
  if (user && user.emailVerified) {
    firebase.database().ref('/users/' + user.uid).once('value').then(function(snapshot) {
      var data = snapshot.val()
      if (data) dispatchUserSignedInEvent(Object.assign(data, {token: user.refreshToken}))
    })
  }
  else {
    dispatchUserSignedOutEvent()
  }
})
