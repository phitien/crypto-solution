import React from 'react'

import Component from './Component'

export default class Copyright extends Component {
  get cmpClassName() {return 'copyright'}
  renderChildren() {return <span>@ {this.config.AppName} <span className='year'>{new Date().getFullYear()}</span></span>}
}
