import React from 'react'

import {Form, Input, Button, Steps, Step} from '../components'
import {BasePage} from './BasePage'

export class PasswordPage extends BasePage {
  get cmpId() {return 'page-password'}
  get private() {return true}
  passwordValid = e => {
    if (this.state.opassword && this.state.password && this.state.password.length >= 6 && this.state.password == this.state.confirm) {
      this.opassword.error = null
      this.password.error = null
      this.confirm.error = null
      return true
    }
    if (!this.state.opassword) {
      this.opassword.error = 'Please provide your old password'
      this.password.error = null
      this.confirm.error = null
    }
    if (!this.state.password || this.state.password.length < 6) {
      this.opassword.error = null
      this.password.error = 'Password is invalid, must have at least 6 characters'
      this.confirm.error = null
    }
    else if (this.state.password != this.state.confirm) {
      this.opassword.error = null
      this.password.error = null
      this.confirm.error = 'Password confirm does not match'
    }
  }
  showError = err => this.actions.Application_AddModal({header: 'Error!', content: <p>Some error occurred: <span className='red-text'>{err && err.message || ''}</span>.<br/> Please try again.</p>})
  showSuccess = e => this.actions.Application_AddModal({header: 'Congratulation!', content: <p>You have changed password successfully</p>})
  onClickSave = e => {
    let valid = this.passwordValid()
    if (valid) {
      if (this.config.firebase) {
        firebase.auth()
        .signInWithEmailAndPassword(this.User.email, this.state.opassword)
        .then(user => {
          firebase.auth().currentUser.updatePassword(this.state.password)
          .then(e => this.showSuccess(e))
          .catch(err => this.showError(err))
        })
        .catch(err => this.showError(err))
      }
      else this.actions.ChangePassword(this.state).then(e => {
        if (e.status == 200) this.showSuccess()
        else this.showError(err)
      })
    }
  }
  renderMainContent() {return <Form className='inline form-password'>
    <div className='heading'>Password change</div>
    <p>
      - All the usual warning about good passwords apply, plus it's your money!<br/>
      - For your protection, we must be very conservative when processing password reset requests.
    </p>
    <Input autocomplete='off' name='opassword' ref={e => this.opassword = e} type='password' value={this.state.password} label='Old password' placeholder='Old password' onChange={e => this.setState({opassword: e.target.value})}/>
    <hr/>
    <Input name='password' ref={e => this.password = e} type='password' value={this.state.password} label='New password' placeholder='New password' onChange={e => this.setState({password: e.target.value})}/>
    <Input name='confirm' ref={e => this.confirm = e} type='password' value={this.state.confirm} label='Confirm password' placeholder='Confirm password' onChange={e => this.setState({confirm: e.target.value})}/>
    <div className='actions'>
      <Button className={`btn-submit`} onClick={e => this.onClickSave()}>Change</Button>
    </div>
  </Form>}
}
