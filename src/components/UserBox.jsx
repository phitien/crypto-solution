import React from 'react'

import Component from './Component'
import Button from './Button'

import {connect} from '../utils'

class UserBox extends Component {
  get cmpClassName() {return `user-box`}
  onClick = v => this.actions.Application_SwitchForm(v).then(e => this.open(this.utils.baseurl('login')))
  onClickSignOut = v => this.actions.User_SignOut().then(e => this.open(this.utils.baseurl()))
  renderUserInfo() {return <div {...{
    onClick: e => this.open('account'),
    className: 'user-info',
    title: typeof this.config.userHint == 'function' ? this.config.userHint(this.User) : ''
  }}>
    <div className='name'>
      {this.displayName}
      <div className='status'>
        {this.config.userVerified(this.User) ? 'verified' : 'not verified'}
      </div>
    </div>
    <div className='avatar' style={this.avatar ? {backgroundImage: `url(${this.avatar})`} : {}}/>
  </div>}
  renderLogged() {return <div className='actions'>
    {this.renderUserInfo()}
    <i className='material-icons' onClick={e => this.onClickSignOut()}>exit_to_app</i>
  </div>}
  renderUnlogged() {return <div className='actions'>
    <Button className={`btn-signup transparent ${this.props.Application.form != 'signin' ? 'bordered' : ''}`} onClick={e => this.onClick('signup')}>Sign Up</Button>
    <Button className={`btn-signin transparent ${this.props.Application.form == 'signin' ? 'bordered' : ''}`} onClick={e => this.onClick('signin')}>Sign In</Button>
  </div>}
  renderChildren() {
    return this.logged ? this.renderLogged() : this.renderUnlogged()
  }
}

export default connect(UserBox)
