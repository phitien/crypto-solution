import {requestHeader} from '../utils'

export const headerMiddleware = store => next => action => {
  const {User} = store.getState().User, {token} = User
  requestHeader('token', token)
  next(action)
}
