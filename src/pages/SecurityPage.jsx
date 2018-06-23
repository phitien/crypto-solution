import React from 'react'

import {Form, Input, Button, Table} from '../components'
import {BasePage} from './BasePage'

export class SecurityPage extends BasePage {
  get cmpId() {return 'page-security'}
  get private() {return true}
  renderMainContent() {return <Form className='inline'>
    <div className='heading'>Two-Factor Authentication</div>
    <p>Two-Factor Authentication makes your account strongly secure.</p>
    <div className='horizontal space-between'>
      <div>Two-factor authentication</div>
      <div>Google Authenticator</div>
      <div>Enable Authentication</div>
    </div>
    <hr/>
    <div className='heading'>Latest Activity</div>
    <p>The activity history displays information about time, IP addresses and devices from which your profile has been recently accessed. If you see any suspicious activity, you can terminate it here.</p>
    <Table api={this.config.api.securities}/>
  </Form>}
}
