import React from 'react'

import Input from './Input'

export default class InputCountry extends Input {
  get type() {return 'select'}
  get textField() {return 'name'}
  get valueField() {return 'alpha3Code'}
  get api() {return this.config.api.countries}
}
