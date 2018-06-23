import {store} from '../../store'

export default function(name, act, uri, method, filter, type) {
  switch (`${name}_${act}`) {
		case `${name}_SignUp`: {
      if (config.firebase) {
        return function(queryParams, postParams, headers) {
          return firebase.auth().createUserWithEmailAndPassword(postParams.email, postParams.password)
          .then(res => {
            const user = res.user, uid = user.uid
            if (uid) {
              const state = store.getState()[name]
              const data = state.fields.reduce((rs,k) => {
                rs[k] = postParams[k] || ''
                return rs
              }, {})
              return firebase.database().ref('users/' + uid).set({...data, uid, username: uid})
              .then(e => user.sendEmailVerification())
              .then(e => Promise.resolve({status: 200, data}))
            }
            else return firebase.auth().signOut()
            .then(e => Promise.reject({status: 400, data: {code: 'UserNotRegistered', message: 'User is not registered successfully'}}))
          })
          .catch(err => Promise.reject({status: 400, data: err}))
        }
      }
    }
    case `${name}_SignIn`: {
      if (config.firebase) {
        return function(queryParams, postParams, headers) {
          return firebase.auth().signInWithEmailAndPassword(postParams.email, postParams.password)
          .then(res => {
            const user = res.user, uid = user.uid, emailVerified = user.emailVerified
            if (!emailVerified) return Promise.reject({message: 'You have not verified your email.'})
            else return Promise.resolve({status: 200, data: user})
          })
          .catch(err => Promise.reject({status: 400, data: err}))
        }
      }
    }
    case `${name}_SignOut`: {
      if (config.firebase) {
        return function(queryParams, postParams, headers) {
          return firebase.auth().signOut()
        }
      }
    }
  }
}
