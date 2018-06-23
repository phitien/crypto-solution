import React from 'react'

import Component from './Component'

export default class Input extends Component {
  initialState() {
    this.state = {
      ...super.initialState(),
      optionsShown: false,
      options: this.props.options,
      value: this.props.value || this.props.defaultValue,
      type: this.props.type || 'text',
      isPassword: this.props.type == 'password',
      textField: this.props.textField || 'name',
      valueField: this.props.valueField || 'value',
    }
    return this.state
  }
  get cmpClassName() {
    const rs = ['input', this.type]
    rs.push(this.error ? 'invalid' : 'valid')
    if (this.optionsShown) rs.push('options-shown')
    if (this.isPassword && this.type != 'password') rs.push('password-shown')
    return rs.join(' ')
  }
  get isPassword() {return this.state.isPassword}
  get type() {return this.state.type}
  set type(v) {this.setState({type: v})}
  get required() {return this.props.required !== undefined && this.props.required !== false && this.props.required !== null}
  get placeholder() {return this.props.placeholder ? `${this.props.placeholder} ${this.required ? '(required)' : ''}` : ''}
  get error() {return this.state.error}
  set error(v) {this.setState({error: v})}
  get value() {return this.state.value}
  set value(v) {
    this.state.value = v
    this.state.error = null
    this.refreshing = true
    if (this.props.onChange) this.props.onChange({target: {...this.selected, value: v}})
  }
  get optionsShown() {return this.state.optionsShown}
  set optionsShown(v) {this.setAttr('optionsShown', v)}
  get textField() {return this.state.textField}
  set textField(v) {this.setAttr('textField', v)}
  get valueField() {return this.state.valueField}
  set valueField(v) {this.setAttr('valueField', v)}
  get selected() {return this.options.find(o => o.value == this.state.value) || {}}
  get options() {return [].concat(this.state.options).filter(o => o && !o.hidden)}
  set options(v) {this.setAttr('options', v)}
  get apiSuccess() {
    return data => {
      if (this.type == 'select') {
        this.options = [].concat(data).filter(o => o).map(o => ({name: o[this.textField], value: o[this.valueField], origin: o}))
      }
    }
  }

  getLabel = o => o && (o.name || o.label || o.title) || ''
  onChange = e => {
    const text = e.target.value
    if (this.type == 'select') {
      const options = [].concat(this.state.options)
      this.optionsShown = true
      if (!text) options.map(o => o.hidden = false)
      else options.map(o => o.hidden = !this.getLabel(o).toLowerCase().includes(text.toLowerCase()))
    }
    else this.value = e.target.value
  }
  onFocus = e => {
    if (this.type == 'select') this.optionsShown = true
    if (this.props.onClick) this.props.onClick(e)
  }
  onBlur = e => {
    setTimeout(e => {
      if (this.type == 'select') this.optionsShown = false
      if (this.props.onClick) this.props.onClick(e)
    }, 100)
  }
  onClickOption = (e, o) => {
    e.preventDefault()
    e.stopPropagation()
    this.value = o.value
  }

  renderLabel() {
    const label = this.props.label || this.props.title || this.props.text
    return label ? <div className='label' onClick={e => this.input.focus()}>{label}{this.required ? ' (*)' : ''}</div> : null
  }
  renderDescription() {
    const description = this.props.description
    return description ? <div className='description' onClick={e => this.input.focus()}>{description}</div> : null
  }
  renderHint() {
    const hint = this.props.hint || this.props.tooltip
    return hint ? <div className='hint'>{hint}</div> : null
  }
  renderError() {
    return typeof this.error == 'string' ? <div className='error'>{this.error}</div> : null
  }
  renderOption(o) {
    return <div className={`option ${this.selected && this.selected.name == o.name ? 'selected' : ''}`} onClick={e => {
      this.onClickOption(e, o)
    }}>{this.getLabel(o)}</div>
  }
  renderOptions() {
    if (this.type != 'select' || !this.optionsShown) return null
    return <div className='options'>
      {this.options.map(o => this.renderOption(o))}
    </div>
  }
  renderIcon() {
    if (!this.isPassword) return null
    return <i onClick={e => this.setState({type: this.type == 'password' ? 'text' : 'password'})} className='material-icons'>remove_red_eye</i>
  }
  renderChildren() {
    return [
    this.renderLabel(),
    this.renderDescription(),
    <div className='field-wrapper'>
      {this.type == 'textarea' ?
      <textarea {...this.utils.exclude(this.props, 'type', 'className', 'placeholder', 'onChange')}
        className='field'
        placeholder={this.placeholder}
        value={this.value}
        onChange={this.onChange}
        ref={e => this.input = e}
      />:
      <input {...this.utils.exclude(this.props, 'type', 'className', 'placeholder', 'onChange')}
        className='field'
        placeholder={this.placeholder}
        onFocus={this.onFocus}
        onBlur={this.onBlur}
        onChange={this.onChange}
        value={this.type == 'select' ? this.selected.name : this.value}
        type={this.type == 'select' ? 'text' : this.type}
        ref={e => this.input = e}
      />}
        {this.renderHint()}
        {this.renderError()}
        {this.renderOptions()}
        {this.renderIcon()}
    </div>,
  ]}
}
