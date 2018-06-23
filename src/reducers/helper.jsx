import {log} from '../utils'
import * as persit from './persit'

const {MAX_PAGE} = config

export function stateToProps(name, state, action, loadmore) {
  const subname = `${name}s`, prop = `${name}_${name}`, props = `${name}_${subname}`

  switch (action.type) {
    case `${name}_Load`: {
      const data = state[name] = {...state[name], ...action.payload || {}}
      return state
    }
    case `${name}_Clear`: {
      state.message = false
      state.error = false
      state.loading = false
      return state
    }
    case `${name}_Error`: {
      return {...state, error: action.payload, loading: false}
    }
    case `${name}_Save`: {
      state[name] = {...state[name], ...action.payload || {}}
      if (persit[`persit${name}`]) persit[`persit${name}`](state[name])
      return state
    }
    case `${name}_Unload`: {
      state[name] = {}
      if (persit[`persit${name}`]) persit[`persit${name}`](state[name])
      return state
    }
    case `${prop}_Pending`: {return {...state, loading: true}}
    case `${prop}_Success`: {
      state[name] = {...state[name], ...action.payload || {}}
      if (persit[`persit${name}`]) persit[`persit${name}`](state[name])
      state.loading = false
      return state
    }
    case `${prop}_Failure`: {return {...state, loading: false}}

    //subname
    case `${name}_Select`: {
      const item = state[subname].list.find(o => {
        o.selected = false
        return o.id == action.payload.id
      })
      if (item) item.selected = true
      return state
    }
    case `${name}_LoadAll`: {
      const data = state[subname] = {...state[subname], ...action.payload || {}}
      return state
    }
    case `${name}_SaveAll`: {
      state[subname] = {...state[subname], ...action.payload || {}}
      if (persit[`persit${subname}`]) persit[`persit${subname}`](state[subname])
      return state
    }

    case `${name}_Loadmore`: {
      let page = state[subname].filter.page
      state[subname].filter.page = page < MAX_PAGE ? page + 1 : page
      return state
    }
    case `${name}_Search`: {
      state[subname].filter = {...state[subname].filter, ...action.payload, page: 0}
      return state
    }
    case `${name}_Reset`: {
      state.error = false
      state.message = false
      state[subname].filter.page = 0
      state[subname].list = []
      state[subname].loaded = false
      return state
    }

    case `${props}_Pending`: {return {...state, loading: true}}
    case `${props}_Success`: {
      state[subname].list = [].concat(loadmore ? state[subname].list : null).concat(action.payload)
        .filter(o => o)
        .reduce((rs, o) => {
          if (!rs.find(c => c.id == o.id)) rs.push(o)
          return rs
        }, [])
      state[subname].loaded = true
      return {...state, loading: false}
    }
    case `${props}_Failure`: {return {...state, loading: false}}
  }
}
