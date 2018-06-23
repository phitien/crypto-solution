import * as models from '../models'
import {apis} from '../apis'
import {log} from '../utils'

function actionGenerator(name, act) {
  const n = `${name}_${act}`
  return function(payload) {
    return dispatch => {
      dispatch({type: n, payload})
      return new Promise((resolve, reject) => {
        resolve({type: n, payload})
      })
    }
  }
}
function apiActionGenerator(name, act, uri, method, filter, type) {
  const n = `${name}_${act}`
  return function(params, payload, ...args) {
    return dispatch => {
      dispatch({type: `${n}_Pending`})
      let api = apis[`${n}`],
        apicall = method == 'post' ? api(payload, params, ...args) : api(params, payload, ...args)
      return apicall
        .then(res => {
          dispatch({type: `${n}_Success`, payload: res.data, response: res})
          return res
        })
        .catch(res => {
          dispatch({type: `${n}_Failure`, payload: res.data, response: res})
          return Promise.reject(res)
        })
    }
  }
}

const basicActions = {
  Error: 'Error',
  Load: 'Load',
  Clear: 'Clear', Save: 'Save', Unload: 'Unload',
  LoadAll: 'LoadAll',
  SaveAll: 'SaveAll',
  Loadmore: 'Loadmore',
  Reset: 'Reset', Search: 'Search', Select: 'Select',
}
const actions = {}
Object.keys(models).map(name => {
  const model = models[name] || {}, acts = {...basicActions, ...model.acts || {}}
  Object.keys(acts).map(act => {
    const value = acts[act]
    if (value.indexOf('api') == 0) {
      const [api, method, filter, type, uri] = value.split('|')
      actions[`${name}_${act}`] = apiActionGenerator(name, act, uri, method, filter == 'true', type)
    }
    else {
      actions[`${name}_${act}`] = actionGenerator(name, act)
    }
  })
})
export {
  actions
}
