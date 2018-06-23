import React from 'react'

import {Form, Input, Button, Steps, Step} from '../components'
import {BasePage} from './BasePage'

export class BountyPage extends BasePage {
  get cmpId() {return 'page-bounty'}
  get html() {return 'bounty.html'}
  renderMainContent() {return <Form className='inline'>
  </Form>}
}
