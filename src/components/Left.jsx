import React from 'react'

import Component from './Component'
import Menu from './Menu'
import Logo from './Logo'
import UserBox from './UserBox'

import {connect} from '../utils'

class Left extends Component {
  initialState() {return this.state = {
    ...super.initialState(),
    collapsed: false,
    showing: false,
  }}
  get collapsed() {return this.state.collapsed}
  set collapsed(v) {this.setAttr('collapsed', v)}
  get showing() {return this.state.showing}
  set showing(v) {this.setAttr('showing', v)}
  get items() {return [].concat(this.props.items).filter(o => o)}
  get cmpClassName() {return `left ${this.showing ? 'open' : ''}`}

  componentDidMount() {
    super.componentDidMount()
    if (this.utils.isMobile()) {
      const left = jQuery(this.dom)
      left.draggable({containment: 'parent', axis: 'x'})
    }
  }

  onClick = e => {
    if (this.utils.isMobile()) {
      e.preventDefault()
      e.stopPropagation()
      this.state.showing = !this.state.showing
      const left = jQuery(this.dom)
      if (this.state.showing) left.addClass('open').show('slide', {direction: 'right'}, 500)
      else left.hide('slide', {direction: 'left'}, 500, e => left.removeClass('open').show())
    }
  }

  onClickCollapse = e => {
    e.preventDefault()
    e.stopPropagation()
    this.state.collapsed = !this.state.collapsed
    const o = jQuery(this.dom), menu = o.find('.menu.menu0')
    const duration = this.config.leftMenuAnimationDuration
    const cb = e => {
      o.find('.collapsing-icon').text(this.state.collapsed ? 'arrow_forward' : 'arrow_back')
      o.toggleClass('collapsed').show('slow')
    }
    if (this.state.collapsed) o.hide('slide', { direction: 'left' }, 500, cb)
    else o.show('slide', { direction: 'left' }, 1000, cb)
  }

  renderLeftContent() {return <Menu items={this.items}/>}
  renderChildren() {
    return [
      !this.showing ? <i onClick={e => this.onClick(e)} className='material-icons mobile menu-icon'>menu</i> : null,
      <i onClick={e => this.onClickCollapse(e)} className='material-icons collapsing-icon'>{this.state.collapsed ? 'arrow_forward' : 'arrow_back'}</i>,
      <div className='wrapper'>
        <div className='mobile'>
          <Logo/>
          <UserBox/>
        </div>
        {this.renderLeftContent()}
      </div>,
    ].filter(o => o)
  }

}
export default connect(Left)
