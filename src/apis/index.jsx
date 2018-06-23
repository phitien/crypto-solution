import * as models from '../models'
import {store} from '../store'
import {apiCall, query, url, apiurl, log} from '../utils'

import * as custom from './custom'

function apiGetGenerator(name, act, uri, method, filter, type) {
  if (custom[name]) {
    const api = custom[name](name, act, uri, method, filter, type)
    if (api) return api
  }
  return function(queryParams, postParams, headers) {
    const o = apiurl(uri), ourl = url(o, query(queryParams))
    const state = store.getState()[name], actState = state[act] || {}
    if (filter) queryParams = {...actState.filter, ...queryParams}
    if (method == 'post' && type == 'form') {
      return apiCall.post(ourl, query(postParams), {headers: {
        ...apiCall.defaults.headers,
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
      }})
    }
    return apiCall[method](ourl, postParams)
  }
}

const apis = {}
Object.keys(models).map(name => {
  const model = models[name] || {}, acts = model.acts || {}
  Object.keys(acts).map(act => {
    const value = acts[act]
    if (value.indexOf('api') == 0) {
      const [api, method, filter, type, uri] = value.split('|')
      apis[`${name}_${act}`] = apiGetGenerator(name, act, uri, method, filter == 'true', type)
    }
  })
})

export {
  apis
}
