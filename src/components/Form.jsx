import React from 'react'

import Component from './Component'

export default class Form extends Component {
  get cmpClassName() {return 'form'}
  render() {
    return <form {...this.props} className={this.className} onClick={this.onClick}>{this.children}</form>
  }
}
