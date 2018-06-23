import React from 'react'

import {Form, Input, Button} from '../components'
import {BasePage} from './BasePage'

export class LoginPage extends BasePage {
  get cmpId() {return 'page-login'}
  get private() {return true}
}
