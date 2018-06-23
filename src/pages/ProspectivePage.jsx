import React from 'react'

import {Form, Input, Button, ContactUs, Steps, Step} from '../components'
import {BasePage} from './BasePage'

export class ProspectivePage extends BasePage {
  get cmpId() {return 'page-prospective'}
  get html() {return 'prospective.html'}
  renderMainContent() {return <Form className='inline'>
    <p>If you would like to implement ICOSID for your ICO, please <a onClick={e => this.actions.Application_AddModal({header: 'Contact Us', content: <ContactUs/>})}>Contact Us</a></p>
  </Form>}
}
