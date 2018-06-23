import React from 'react'

import Component from './Component'
import Logo from './Logo'
import Menu from './Menu'
import Space from './Space'
import UserBox from './UserBox'

import {connect} from '../utils'

class Header extends Component {
  get cmpClassName() {return 'header'}
  componentDidMount() {
    super.componentDidMount()
    if (!this.props.Application.Menus.loaded)
      this.actions.Application_Menus()
  }
  renderChildren() {return this.props.children || [
    <Logo/>,
    <Space/>,
    <Menu className='top' items={this.props.Application.Menus.top}/>,
    <UserBox/>
  ]}
}
export default connect(Header)
