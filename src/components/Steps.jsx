import React from 'react'

import Component from './Component'
import Button from './Button'
import Step, {stepName, getSteps} from './Step'

export default class Steps extends Component {
  initialState() {return {
    ...super.initialState(),
    current: this.props.current || 0,
    stopJumping: this.props.stopJumping,
  }}
  get cmpClassName() {return 'steps'}
  get stopJumping() {return this.state.stopJumping}
  set stopJumping(v) {this.setAttr('stopJumping', v)}
  get current() {return this.state.current}
  set current(v) {this.setAttr('current', v || 0)}
  get currentStep() {
    return this.steps.length ? this.steps[this.current] : null
  }
  get steps() {return getSteps(super.children).map((o,i) => {
    return {...o, props: {
      ...o.props,
      className: `${o.props.className || ''} ${i == this.current ? 'active' : ''}`,
      validate: typeof o.props.validate == 'function' ? o.props.validate : e => true,
      onNext: typeof o.props.onNext == 'function' ? o.props.onNext : e => true,
    }}
  })}
  get children() {return []
    .concat(this.renderHeader())
    // .concat(this.renderHeading())
    .concat(this.steps)
    .concat(this.renderActions())
  }
  componentDidUpdate() {
    jQuery('.step-header-item').each(function() {
      let me = jQuery(this), w = me.outerWidth() - me.find('.step-header-item-index').outerWidth()
      me.find('.step-header-item-line').width(w/2 - 3)
    })
  }
  onClickPrev(e) {
    if (this.current > 0) this.current = this.current - 1
  }
  onClickNext(e) {
    const steps = this.steps, current = this.current, currentStep = this.currentStep
    if (current <= steps.length - 1) {
      if (this.currentStep.props.validate()) {
        if (current != steps.length - 1) this.current = current + 1
        this.currentStep.props.onNext()
      }
    }
  }
  renderHeading() {
    return <div className='heading'>{this.currentStep ? stepName(this.currentStep.props) : null}</div>
  }
  renderActions() {
    const steps = this.steps, current = this.current
    return <div className='actions'>
      {current == 0 ? null : <Button onClick={e => this.onClickPrev(e)}>Previous</Button>}
      <Button onClick={e => this.onClickNext(e)}>{current == steps.length - 1 ? 'Finish' : 'Next'}</Button>
    </div>
  }
  renderHeader() {
    const steps = this.steps
    return <div className='step-header'>
      {steps.map((o,i) => <div onClick={e => this.stopJumping ? false : this.current = i} className={`step-header-item ${i == this.current ? 'active' : ''}`}>
        <div className='step-header-item-line step-header-item-line-before'></div>
        <div className='step-header-item-index'>{i + 1}</div>
        <div className='step-header-item-name'>{stepName(o.props)}</div>
        <div className='step-header-item-line step-header-item-line-after'></div>
      </div>)}
    </div>
  }
}
