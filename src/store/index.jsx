import {createStore, applyMiddleware, compose} from 'redux'
import thunk from 'redux-thunk'

import {headerMiddleware} from '../middlewares'
import {reducers} from '../reducers'
import * as models from '../models'

export function appstore() {
  const middleware = applyMiddleware(thunk, headerMiddleware)
  const enhancer = compose(middleware)
  return createStore(reducers, models, enhancer)
}

export const store = appstore()
