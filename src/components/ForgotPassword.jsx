import React from 'react'
import Component from './Component'
import Form from './Form'
import Button from './Button'
import Input from './Input'
import InputCountry from './InputCountry'

import {connect} from '../utils'

class ForgotPassword extends Component {
  get cmpClassName() {return 'forgot-password'}
  emailValid = e => {
    if (this.utils.validateEmail(this.state.email)) {
      this.emailInput.error = null
      return true
    }
    this.emailInput.error = 'Email is invalid'
  }
  onClickSwitchForm = v => this.actions.Application_SwitchForm(v)
  showError = err => this.actions.Application_AddModal({header: 'Error!', content: <p>Some error occurred: <span className='red-text'>{err && err.message || ''}</span><br/> Please try again.</p>})
  showSuccess = e => this.actions.Application_AddModal({header: 'Congratulation!', content: <p>A reset password email has been sent to <span className='red-text'>{this.state.email}</span>. Thanks</p>})
  onClickForgotPassword = e => {
    let valid = this.emailValid()
    if (valid) {
      if (this.config.firebase) {
        firebase.auth().sendPasswordResetEmail(this.state.email)
        .then(res => this.showSuccess())
        .catch(err => this.showError(err))
      }
      else this.actions.User_ForgotPassword(this.state).then(e => {
        if (e.status == 200) this.showSuccess()
        else this.showError(e)
      })
    }
  }
  render() {
    return <div className={this.className}>
      <div className='heading'>Forgot Password</div>
      <Form autofill='off'>
        <Input ref={e => this.emailInput = e} type='email' value={this.state.email}
          required={true} name='email'
          label='What is your email address?'
          placeholder='Email Address' onChange={e => this.setState({email: e.target.value})}/>
        <div className='actions'>
          <Button className={`btn-submit`} onClick={e => this.onClickForgotPassword()}>Submit</Button>
        </div>
      </Form>
      <div className='switch'>
        <span onClick={e => this.onClickSwitchForm('signin')}>Sign in</span>
        <span onClick={e => this.onClickSwitchForm('signup')}>Sign up</span>
      </div>
    </div>
  }
}

export default connect(ForgotPassword)
