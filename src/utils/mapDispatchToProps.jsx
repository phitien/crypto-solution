import {bindActionCreators} from 'redux'
import {actions} from '../actions'
import * as models from '../models'

export function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators(actions, dispatch),
    dispatch
  }
}
