import * as models from '../models'
import {log} from '../utils'
import {stateToProps} from './helper'

import * as custom from './custom'

function reducerGenerator(name) {
  const initialState = models[name] || {}
  const {loadmore} = initialState
  return function(state = initialState, action) {
    if (typeof action.payload == 'object' && action.payload && action.payload.hasOwnProperty('dispatchConfig')) return state
    let rs
    if (custom[name]) rs = custom[name](name, state, action, initialState)
    if (!rs) rs = stateToProps(name, state, action, loadmore)
    if (!rs) rs = state
    return {...rs, __flag: !rs.__flag}
  }
}

const all = {}
Object.keys(models).map(name => {
  const model = models[name] || {}
  all[name] = reducerGenerator(name)
})
export default all
