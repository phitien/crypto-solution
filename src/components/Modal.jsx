import React from 'react'

import Component from './Component'

import {connect} from '../utils'

class Modal extends Component {
  get cmpClassName() {return 'm-overlay'}
  removeModal = e => this.actions.Application_RemoveModal(this.props.data)
  renderHeader() {return this.props.header ? <div className='m-header'>{this.props.header}</div> : null}
  renderContent() {
    const content = this.props.children || this.props.content
    return content ? <div className='m-content'>{content}</div> : null
  }
  renderFooter() {return this.props.footer ? <div className='m-footer'>{this.props.footer}</div> : null}
  renderCloseIcon() {return <div className='m-close' onClick={this.removeModal}><i className='material-icons'>close</i></div>}
  renderChildren() {return <div className='m-container'>
    {this.renderHeader()}
    {this.renderContent()}
    {this.renderFooter()}
    {this.renderCloseIcon()}
  </div>}
}
export default connect(Modal)
