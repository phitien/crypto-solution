import React from 'react'

import Component from './Component'
import Button from './Button'

export const stepName = o => o.name || o.title || o.label || o.heading
export const getSteps = o => [].concat(o).filter(o => o && o.props && stepName(o.props))

export default class Step extends Component {
  initialState() {return {
    ...super.initialState(),
    name: stepName(this.props)
  }}
  get cmpClassName() {return 'step'}
  get name() {return this.state.name}
  set name(v) {this.setAttr('name', v)}
}
