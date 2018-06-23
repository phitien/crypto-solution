import React from 'react'
import Component from './Component'
import Form from './Form'
import Button from './Button'
import Input from './Input'

import {connect} from '../utils'

class ContactUs extends Component {
  initialState() {return this.state = {
    ...super.initialState(),
    name: this.props.name, email: this.props.email, mobile: this.props.mobile, company: this.props.company, subject: this.props.subject, body: this.props.body
  }}
  get cmpClassName() {return 'contact-us'}
  renderChildren() {
    return <div className='form'>
      <Input autocomplete='off' ref={e => this.name = e} value={this.state.email} placeholder='Your name (required)' onChange={e => this.setState({name: e.target.value})}/>
      <Input autocomplete='off' ref={e => this.email = e} type='email' value={this.state.email} placeholder='Email' onChange={e => this.setState({email: e.target.value})}/>
      <Input autocomplete='off' ref={e => this.mobile = e} value={this.state.email} placeholder='Your mobile (required)' onChange={e => this.setState({mobile: e.target.value})}/>
      <Input autocomplete='off' ref={e => this.company = e} value={this.state.email} placeholder='Your company (required)' onChange={e => this.setState({company: e.target.value})}/>
      <Input autocomplete='off' ref={e => this.subject = e} value={this.state.email} placeholder='Your subject (required)' onChange={e => this.setState({subject: e.target.value})}/>
      <Input autocomplete='off' ref={e => this.body = e} type='textarea' value={this.state.email} placeholder='Your message (required)' onChange={e => this.setState({body: e.target.value})}/>
      <Button onClick={e => this.onClickSignUp()}>Confirm</Button>
    </div>
  }
}
export default connect(ContactUs)
