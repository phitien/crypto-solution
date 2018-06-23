import React from 'react'

import {Form, Input, InputCountry, InputDate, Button} from '../components'
import {BasePage} from './BasePage'

export class AccountPage extends BasePage {
  get cmpId() {return 'page-account'}
  get private() {return true}
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
  birthdayValid = e => {
    if (this.state.birthday) {
      this.birthdayInput.error = null
      return true
    }
    this.birthdayInput.error = 'Please enter your birthday'
  }
  onClickSave = e => {
    let valid = this.fullnameValid()
    // if (valid) valid = this.emailValid()
    if (valid) valid = this.phoneNumberValid()
    if (valid) valid = this.nationalityValid()
    if (valid) valid = this.birthdayValid()
    if (valid) {
      if (this.config.firebase) {
        const data = this.UserFields.reduce((rs,k) => {
          rs[k] = this.state[k] || ''
          return rs
        }, {})
        firebase.database().ref('users/' + this.User.username).set(data, err => {
          if (err) this.actions.Application_AddMessage({message: err.message, error: true})
          else this.actions.Application_AddMessage({message: 'Account saved successfully'})
        })
      }
      else this.actions.User_Update(this.state).then(e => {
        if (e.status == 200) this.actions.Application_AddMessage({message: 'Account saved successfully'})
        else this.actions.Application_AddMessage({message: e.message || 'Some error occurred', error: true})
      })
    }
  }
  renderMainContent() {
    return <Form className='half'>
    <div className='heading'>Account Details</div>
    <div className='horizontal fields-group'>
      <Input ref={e => this.fullnameInput = e} type='text' value={this.state.fullname}
        required={true}
        label='What is your full name?'
        description='Must match the full name on your ID.'
        placeholder='Full Name' onChange={e => this.setState({fullname: e.target.value})}/>
    </div>
    <div className='horizontal fields-group'>
      <Input ref={e => this.lnameInput = e} type='text' value={this.state.lname}
        required={true}
        label='What is your last name?'
        description='Must match the last name on your ID.'
        placeholder='Last Name' onChange={e => this.setState({lname: e.target.value})}/>
    </div>
    <div className='horizontal fields-group'>
      <Input ref={e => this.emailInput = e} type='email' value={this.state.email}
        required={true} disabled
        label='What is your email address?'
        placeholder='Email Address' onChange={e => this.setState({email: e.target.value})}/>
    </div>
    <div className='horizontal fields-group'>
      <Input ref={e => this.phoneNumberInput = e} type='text' value={this.state.phoneNumber}
        required={true}
        label='What is your phone number?'
        description='Please include your country code.'
        placeholder='Phone Number' onChange={e => this.setState({phoneNumber: e.target.value})}/>
    </div>
    <div className='horizontal fields-group'>
      <InputCountry ref={e => this.nationalityInput = e} value={this.state.nationality}
        required={true}
        label='What is your nationality?'
        description='Must match your proof of identity document.'
        placeholder='Nationality' onChange={e => this.setState({nationality: e.target.value})}
      />
    </div>
    <div className='horizontal fields-group'>
      <InputDate ref={e => this.birthdayInput = e} value={this.state.birthday}
        required={true}
        label='What is your birthday?'
        placeholder='Birthday' onChange={e => this.setState({birthday: e.target.value})}
      />
    </div>
    <div className='actions'>
      <Button className={`btn-submit`} onClick={e => this.onClickSave()}>Save</Button>
    </div>
  </Form>}
}
