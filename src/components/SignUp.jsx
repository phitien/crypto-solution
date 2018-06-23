import React from 'react'
import Component from './Component'
import Form from './Form'
import Button from './Button'
import Input from './Input'
import InputCountry from './InputCountry'

import {connect} from '../utils'

class SignUp extends Component {
  initialState() {return this.state = {
    ...super.initialState(),
    // phoneNumber: '97728266',
    // fullname: 'Phi Duc Tien',
    // email: 'im.phitien@gmail.com',
    // nationality: 'VNM',
    // password: 'tien123',
    // confirm: 'tien123',
    step: 0,
  }}
  get cmpClassName() {return 'sign-up'}
  fullnameValid = e => {
    if (this.state.fullname) {
      this.fullnameInput.error = null
      return true
    }
    this.fullnameInput.error = 'Please enter your full name'
  }
  emailValid = e => {
    if (this.utils.validateEmail(this.state.email)) {
      this.emailInput.error = null
      return true
    }
    this.emailInput.error = 'Email is invalid'
  }
  phoneNumberValid = e => {
    if (this.state.phoneNumber) {
      this.phoneNumberInput.error = null
      return true
    }
    this.phoneNumberInput.error = 'Please enter your phone number'
  }
  nationalityValid = e => {
    if (this.state.nationality) {
      this.nationalityInput.error = null
      return true
    }
    this.nationalityInput.error = 'Please enter your nationality'
  }
  passwordValid = e => {
    if (this.state.password && this.state.password.length >= 6 && this.state.password == this.state.confirm) {
      this.passwordInput.error = null
      this.confirmInput.error = null
      return true
    }
    if (!this.state.password || this.state.password.length < 6) {
      this.passwordInput.error = 'Password is invalid, must have at least 6 characters'
      this.confirmInput.error = null
    }
    else if (this.state.password != this.state.confirm) {
      this.passwordInput.error = null
      this.confirmInput.error = 'Password confirm does not match'
    }
  }
  onPrev = v => {
    this.state.step--
    jQuery(this.dom).find('.step.active').fadeOut(e => {
      jQuery(this.dom).find('.step.active').removeClass('active')
      const next = jQuery(this.dom).find(`.step${this.state.step}`)
      next.fadeIn(e => {
        next.addClass('active')
        if (this.state.step == 0) jQuery(this.dom).find('.btn-prev').addClass('hidden')
        jQuery(this.dom).find('.btn-next').removeClass('hidden')
        jQuery(this.dom).find('.btn-submit').addClass('hidden')
      })
    })
  }
  onNext = v => {
    let valid = false
    if (this.state.step == 0) valid = this.fullnameValid() && this.emailValid() && this.phoneNumberValid() && this.nationalityValid()
    // if (this.state.step == 1) valid = this.phoneNumberValid() && this.nationalityValid()
    if (valid) {
      this.state.step++
      jQuery(this.dom).find('.step.active').fadeOut(e => {
        jQuery(this.dom).find('.step.active').removeClass('active')
        const next = jQuery(this.dom).find(`.step${this.state.step}`)
        next.fadeIn(e => {
          next.addClass('active')
          jQuery(this.dom).find('.btn-prev').removeClass('hidden')
          if (this.state.step == 1) jQuery(this.dom).find('.btn-next').addClass('hidden')
          if (this.state.step == 1) jQuery(this.dom).find('.btn-submit').removeClass('hidden')
        })
      })
    }
  }
  onClickSwitchForm = v => this.actions.Application_SwitchForm(v)
  showError = err => this.actions.Application_AddModal({header: 'Error!', content: <p>Some error occurred: <span className='red-text'>{err && err.message || ''}</span><br/> Please try again.</p>})
  showSuccess = user => this.actions.Application_AddModal({header: 'Congratulation!', content: <p>You have successfully signed up as <span className='red-text'>{this.state.email}</span>.<br/>Please verify your account in your email. Thanks</p>})
  .then(e => this.onClickSwitchForm('signin'))
  .then(e => this.open(this.utils.baseurl()))
  onClickSignUp = e => {
    let valid = this.passwordValid()
    if (valid) {
      this.actions.Application_Indicator(true)
      this.actions.User_SignUp(this.state)
      .then(res => this.showSuccess(res.data))
      .catch(res => this.showError(res.data))
      .finally(res => this.actions.Application_Indicator(false))
    }
  }
  render() {
    return <div className={this.className}>
      <div className='heading'>Sign Up</div>
      <Form autofill='off'>
        <div className='fields-group step step0 active'>
          <Input ref={e => this.emailInput = e} type='email' value={this.state.email}
            required={true} name='email'
            label='What is your email address?'
            placeholder='Email Address' onChange={e => this.setState({email: e.target.value})}/>
          <Input ref={e => this.fullnameInput = e} type='text' value={this.state.fullname}
            required={true} name='fullname'
            label='What is your first name?'
            description='Must match the full name on your ID.'
            placeholder='Full Name' onChange={e => this.setState({fullname: e.target.value})}/>
          <Input ref={e => this.phoneNumberInput = e} type='text' value={this.state.phoneNumber}
            required={true} name='phoneNumber'
            label='What is your phone number?'
            description='Please include your country code.'
            placeholder='Phone Number' onChange={e => this.setState({phoneNumber: e.target.value})}/>
          <InputCountry ref={e => this.nationalityInput = e} value={this.state.nationality}
            required={true} name='nationality'
            label='What is your nationality?' autofill='off'
            description='Must match your proof of identity document.'
            placeholder='Nationality' onChange={e => this.setState({nationality: e.target.value})}
          />
        </div>
        <div className='fields-group step step1'>
          <Input ref={e => this.passwordInput = e} type='password' value={this.state.password}
            required={true} name='password'
            label='Please enter your password'
            placeholder='Password' onChange={e => this.setState({password: e.target.value})}/>
          <Input ref={e => this.confirmInput = e} type='password' value={this.state.confirm}
            required={true} name='confirm'
            label='One more time'
            placeholder='Confirm Password' onChange={e => this.setState({confirm: e.target.value})}/>
        </div>
        <div className='actions'>
          <Button className={`btn-prev ${this.state.step == 0 ? 'hidden' : ''}`} onClick={e => this.onPrev()}>Previous</Button>
          <Button className={`btn-next ${this.state.step == 1 ? 'hidden' : ''}`} onClick={e => this.onNext()}>Next</Button>
          <Button disabled={this.User.loading} className={`btn-submit ${this.state.step < 1 ? 'hidden' : ''}`} onClick={e => this.onClickSignUp()}>Agree & Submit</Button>
        </div>
      </Form>
      <div className='switch'>
        Already have an account? <span onClick={e => this.onClickSwitchForm('signin')}>Sign in</span>
        <br/>
        <span onClick={e => this.onClickSwitchForm('forgot')}>Forgot Password</span>
      </div>
    </div>
  }
}

export default connect(SignUp)
