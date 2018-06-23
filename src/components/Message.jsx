import React, {Component as RAComponent} from 'react'
import ReactDOM from 'react-dom'

import {apis} from '../apis'
import {emitter} from '../emitter'
import * as utils from '../utils'

const {uuid, connect} = utils

class Message extends RAComponent {
  state = {
    message: this.props.message || this.props.children || '',
    error: this.props.error || ''
  }
  componentDidMount() {
    setTimeout(e => this.onClick(e), 7000)
  }
  get message() {return this.props.data && this.props.data.message || this.state.message || ''}
  get error() {return this.props.data ? this.props.data.error : this.state.error}
  onClick(e) {
    if (!this.props.data) return
    this.props.actions.Application_RemoveMessage(this.props.data)
  }
  render() {
    return <div
      onClick={this.onClick.bind(this)}
      className={`${this.props.className || ''} message ${this.error ? 'error' : ''}`}>{this.message}</div>
  }
}
export default connect(Message)
