import React, {Component as RAComponent} from 'react'
import ReactDOM from 'react-dom'

import * as utils from '../utils'

const {uuid, connect} = utils

class Spinner extends RAComponent {
  state = {size: this.props.size || 'normal'}
  render() {
    return <div className={`${this.props.className || ''} spinner spinner-${this.state.size}`}></div>
  }
}
export default connect(Spinner)
