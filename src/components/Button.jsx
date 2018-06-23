import React from 'react'

import Component from './Component'

export default class Button extends Component {
  get cmpClassName() {return `button ${this.props.disabled ? 'disabled' : ''}`}
  renderChildren() {return this.props.children || this.props.title || this.props.label || this.props.text}
  render() {
    return <button {...this.props} type={this.props.type || 'button'} className={this.className} onClick={this.onClick}>{this.children}</button>
  }
}
