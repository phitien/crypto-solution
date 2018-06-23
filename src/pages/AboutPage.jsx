import React from 'react'

import {Form, Input, Button, Steps, Step} from '../components'
import {BasePage} from './BasePage'

export class AboutPage extends BasePage {
  get cmpId() {return 'page-about'}
  get html() {return 'about.html'}
  renderMainContent() {return <Form className='inline'>
  </Form>}
}
