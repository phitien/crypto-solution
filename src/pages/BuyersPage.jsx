import React from 'react'

import {Form, Input, Button, Steps, Step} from '../components'
import {BasePage} from './BasePage'

export class BuyersPage extends BasePage {
  get cmpId() {return 'page-security'}
  get html() {return 'buyers.html'}
  renderMainContent() {return <Form className='inline'>
  </Form>}
}
