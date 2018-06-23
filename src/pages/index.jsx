import {connect} from '../utils'
import * as all from './all'

export default Object.keys(all).reduce((rs, k) => {
  rs[k] = connect(all[k])
  return rs
}, {})
