import React from 'react'

import Component from './Component'

import {connect} from '../utils'

class Content extends Component {
  get cmpClassName() {return 'content'}
}
export default connect(Content)
