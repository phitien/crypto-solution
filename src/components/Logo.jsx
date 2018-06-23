import React from 'react'
import Component from './Component'

export default class Logo extends Component {
  get cmpClassName() {return 'logo'}
  onClick = e => location.pathname != this.config.apppath ? this.open(this.config.apppath) : false
  renderChildren() {return this.props.showName ? this.config.AppName : null}
}
