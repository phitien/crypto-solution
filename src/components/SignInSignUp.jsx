import React from 'react'
import Component from './Component'
import Form from './Form'
import Button from './Button'
import Input from './Input'
import SignUp from './SignUp'
import ForgotPassword from './ForgotPassword'

import {connect} from '../utils'

class SignInSignUp extends Component {
  get cmpClassName() {return 'signin-signup'}
  valid = e => this.emailValid() ? this.passwordValid() : false
  emailValid = e => {
    if (this.utils.validateEmail(this.state.email)) {
      this.emailInput.error = null
      return true
    }
    this.emailInput.error = 'Email is invalid'
  }
  passwordValid = e => {
    if (this.state.password) {
      this.passwordInput.error = null
      return true
    }
    this.passwordInput.error = 'Password is invalid'
  }
  onClickSwitchForm = v => this.actions.Application_SwitchForm(v)
  showError = err => this.actions.Application_AddModal({header: 'Error!', content: <p>Some error occurred: <span className='red-text'>{err && err.message || ''}</span><br/> Please try again.</p>})
  showSuccess = user => this.open(this.utils.baseurl())
  onClickSignIn = e => {
    let valid = this.valid()
    if (valid) {
      this.actions.Application_Indicator(true)
      this.actions.User_SignIn(this.state)
      .then(res => this.showSuccess(res.data))
      .catch(res => this.showError(res.data))
      .finally(res => this.actions.Application_Indicator(false))
    }
  }
  renderSignIn() {
    return <div className='sign-in'>
      <div className='heading'>Sign In</div>
      <Form autocomplete='off' autofill='off'>
        <Input ref={e => this.emailInput = e} type='email' value={this.state.email} label='E-Mail' placeholder='Email' onChange={e => this.setState({email: e.target.value})}/>
        <Input ref={e => this.passwordInput = e} type='password' value={this.state.password} label='Password' placeholder='Password' onChange={e => this.setState({password: e.target.value})}/>
        <div className='actions'>
          <Button onClick={e => this.onClickSignIn()}>Sign In</Button>
        </div>
      </Form>
      <div className='switch'>
        Don't have an account? <span onClick={e => this.onClickSwitchForm('signup')}>Register</span>
        <br/>
        <span onClick={e => this.onClickSwitchForm('forgot')}>Forgot Password</span>
      </div>
    </div>
  }
  renderSignUp() {
    return <SignUp/>
  }
  renderForgotPassword() {
    return <ForgotPassword/>
  }
  renderChildren() {
    return this.props.Application.form == 'signin' ? this.renderSignIn() :
    this.props.Application.form == 'signup' ? this.renderSignUp() :
    this.props.Application.form == 'forgot' ? this.renderForgotPassword() : null
  }
}
export default connect(SignInSignUp)
