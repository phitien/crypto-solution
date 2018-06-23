import React from 'react'

import {Form, Input, InputCountry, InputDate, Button, Steps, Step} from '../components'
import {BasePage} from './BasePage'

export class VerificationPage extends BasePage {
  get cmpId() {return 'page-verification'}
  get private() {return true}
  fullnameValid = e => {
    if (this.state.fullname) {
      this.fullnameInput.error = null
      return true
    }
    this.fullnameInput.error = 'Please enter your fullname'
  }
  genderValid = e => {
    if (this.state.gender) {
      this.genderInput.error = null
      return true
    }
    this.genderInput.error = 'Please specify your gender'
  }
  nationalityValid = e => {
    if (this.state.nationality) {
      this.nationalityInput.error = null
      return true
    }
    this.nationalityInput.error = 'Please give your nationality'
  }
  phoneNumberValid = e => {
    if (this.state.phoneNumber) {
      this.phoneNumberInput.error = null
      return true
    }
    this.phoneNumberInput.error = 'Please give your phone number'
  }
  documentTypeValid = e => {
    if (this.state.documentType) {
      this.documentTypeInput.error = null
      return true
    }
    this.documentTypeInput.error = 'Please specify your document type'
  }
  documentValid = e => {
    if (this.state.document) {
      this.documentInput.error = null
      return true
    }
    this.documentInput.error = 'Please uploade your document'
  }
  onValidateKYC() {
    let valid = this.fullnameValid()
    if (valid) valid = this.genderValid()
    if (valid) valid = this.nationalityValid()
    if (valid) valid = this.phoneNumberValid()
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
    return valid
  }
  onValidatePoR() {
    let valid = this.documentTypeValid()
    // if (valid) valid = this.documentValid()
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
    return valid
  }
  renderMainContent() {return <Form className='form-verification'>
    <div className='heading'>Verification</div>
    <Steps current={0} stopJumping={true}>
      <Step name='Intro' notitle>
        <p>You should obtain two levels of verification: <br/>- KYC by Onfido <br/>- Proof of Residence.<br/>For each subsequent level can not go without passing the previous levels.</p>
      </Step>
      <Step name='KYC' validate={this.onValidateKYC.bind(this)}>
        <p>Complete verification to unlock account opportunities. Verification procedure is very simple and intuitive. We will only ask you to provide us some personal information and supporting documents.</p>
        <div className='warning-block'>
          <i className='material-icons'>warning</i>
          If you provide fake or incorrect documents, we can block your account without any explanation.
        </div>
        <hr/>
        <div className='horizontal fields-group'>
          <Input className='flex2' autocomplete='off' required ref={e => this.fullnameInput = e} value={this.state.fullname} label='Full name' placeholder='Full name' onChange={e => this.setState({fullname: e.target.value})}/>
          <InputDate className='flex1' ref={e => this.birthdayInput = e} value={this.state.birthday} required={true}
            label='Birthday' placeholder='Birthday'
            onChange={e => this.setState({birthday: e.target.value})}
          />
          <Input className='flex1' autocomplete='off' required ref={e => this.genderInput = e} type='select' value={this.state.gender} label='Gender' placeholder='Gender' onChange={e => this.setState({gender: e.target.value})}
            api={this.config.api.genders}/>
        </div>
        <div className='horizontal fields-group'>
          <InputCountry autocomplete='off' required ref={e => this.nationalityInput = e}
            value={this.state.nationality}
            label='Nationality' placeholder='Nationality'
            onChange={e => this.setState({nationality: e.target.value})}
          />
          <Input autocomplete='off' required ref={e => this.phoneNumberInput = e} value={this.state.phoneNumber} label='Mobile' placeholder='Mobile' onChange={e => this.setState({phoneNumber: e.target.value})}/>
        </div>
      </Step>
      <Step name='PoR' validate={this.onValidatePoR.bind(this)}>
        <div className='horizontal fields-group'>
          <Input className='flex1' autocomplete='off' required ref={e => this.documentTypeInput = e} type='select' value={this.state.documentType} label='Document type' placeholder='Document type' onChange={e => this.setState({documentType: e.target.value})}
            api={this.config.api.documentTypes}/>
          <Input className='flex3' autocomplete='off' required ref={e => this.documentInput = e} type='file' value={this.state.document} label='Document' placeholder='Document' onChange={e => this.setState({document: e.target.value})}/>
        </div>
      </Step>
    </Steps>
  </Form>}
}
