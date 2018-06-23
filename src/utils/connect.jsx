require('../prototypes')

import {connect as connectRD} from 'react-redux'

import {mapDispatchToProps} from './mapDispatchToProps'
import {mapStateToProps} from './mapStateToProps'

export function connect(o) {
  let no = connectRD(mapStateToProps, mapDispatchToProps)(o)
  if (!no.prototype) return no
  let descriptors = Object.getOwnPropertyDescriptors(o.prototype), keys = Object.keys(descriptors)
  let ndescriptors = Object.getOwnPropertyDescriptors(no.prototype), nkeys = Object.keys(ndescriptors)
  let exclusion = [/initialState/, /^render.*/]
  let diff = keys.diff(nkeys).filter(k => {
    return !exclusion.reduce((rs,reg) => {
      if (!rs) rs = reg.test(k)
      return rs
    }, false)
  })
  diff.forEach(k => {
    if (!no.prototype) return
    let descriptor = descriptors[k]
    try {
      // Object.defineProperty(no.prototype, k, descriptors[k])
    }
    catch(e) {
      console.log('Failed', o.name, k, e.message, descriptor)
    }
  })
  return no
}
