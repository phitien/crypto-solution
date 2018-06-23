import React, {Component as RAComponent} from 'react'
import ReactDOM from 'react-dom'

import Spinner from './Spinner'
import Message from './Message'

import {apis} from '../apis'
import {emitter} from '../emitter'
import * as utils from '../utils'

const {uuid} = utils

export default class Component extends RAComponent {
  constructor(props) {
    super(props)
    this.state = this.initialState()
  }
  componentDidMount() {
    if (this.dom) this.dom.ezy = this
    if (this.api) this.apiCall()
  }
  UNSAFE_componentWillUpdate(nextProps, nextState) {
    Object.assign(this.state, this.User)
  }
  componentDidUpdate(prevProps, prevState, snapshot) {
  }
  initialState() {return this.state = {
    ...this.User,
    loading: false,
    error: false, message: false, uuid: uuid(),
    api: this.props.api,
    apiParams: this.props.apiParams,
    apiMethod: this.props.apiMethod || 'get',
    apiDataType: this.props.apiDataType,
    apiSuccess: this.props.apiSuccess,
    apiFailure: this.props.apiFailure,
  }}

  setAttr(n, v) {
    this.state[n] = v
    this.refreshing = true
  }

  get klass() {return this.constructor.name}
  get config() {return config}

  get fname() {return this.state.fname}
  get lname() {return this.state.lname}
  get fullname() {return this.state.fullname}
  get email() {return this.state.email}
  get phoneNumber() {return this.state.phoneNumber}
  get nationality() {return this.state.nationality}
  get displayName() {return this.state.displayName || this.state.fullname || [this.state.fname, this.state.lname].filter(o => o).join(' ')}
  get avatar() {return this.state.avatar}

  get apis() {return apis}
  get actions() {return this.props.actions}
  get User() {return this.props.User && this.props.User.User || {}}
  get UserFields() {return this.props.User && this.props.User.fields || []}
  get logged() {return this.User.email && this.User.token}
  get error() {return this.state.error}
  get message() {return this.state.message}
  get utils() {return utils}
  get log() {return utils.log}
  get open() {return url => utils.history.push(url)}
  get openNewTab() {return utils.openNewTab}
  get refreshing() {return this.state.refreshing || false}
  set refreshing(v) {if (v) this.forceUpdate(e => this.state.refreshing = false)}
  get cmpId() {return null}
  get uuid() {return this.state.uuid}
  get cmpClassName() {return ''}
  get className() {return `${this.cmpClassName || ''} ${this.props.className || ''} ${this.cmpId || ''}`}
  get dom() {return ReactDOM.findDOMNode(this)}
  get id() {return this.props.id}
  get children() {
    let children = this.renderChildren()
    if (Array.isArray(children)) {
      children = children.filter(o => o)
      if (!children.length) children = null
    }
    return children
  }
  get api() {return this.state.api}
  set api(v) {
    if (v != this.state.api) this.setAttr('api', v)
  }
  get apiParams() {return this.state.apiParams}
  set apiParams(v) {
    if (v != this.state.apiParams) this.setAttr('apiParams', v)
  }
  get apiMethod() {return this.state.apiMethod}
  set apiMethod(v) {
    if (v != this.state.apiMethod) this.setAttr('apiMethod', v)
  }
  get apiDataType() {return this.state.apiDataType}
  set apiDataType(v) {
    if (v != this.state.apiDataType) this.setAttr('apiDataType', v)
  }
  get apiSuccess() {return this.state.apiSuccess}
  set apiSuccess(v) {
    if (v != this.state.apiSuccess) this.setAttr('apiSuccess', v)
  }
  get apiFailure() {return this.state.apiFailure}
  set apiFailure(v) {
    if (v != this.state.apiFailure) this.setAttr('apiFailure', v)
  }

  onClick = (...args) => this.props.onClick ? this.props.onClick(...args) : false
  apiCall() {
    let uri = this.utils.apiurl(this.api), method = this.apiMethod,
      params = this.apiParams, type = this.apiDataType,
      apiCall = this.utils.apiCall
    if (method == 'get') uri = this.utils.url(uri, this.utils.query({params}))
    if (method == 'post' && type == 'form') {
      return apiCall.post(uri, this.utils.query(params), {headers: {
        ...apiCall.defaults.headers,
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
      }})
    }
    return apiCall[method](uri, params)
    .then(res => {
      if (this.apiSuccess) this.apiSuccess(res.data)
      return res
    })
    .catch(res => {
      if (this.apiFailure) this.apiFailure(res.data)
      return res
    })
  }
  refresh() {this.refreshing = true}
  renderError() {
    return this.error ? <Message error={true} key='error'>{this.error}</Message> : null
  }
  renderMessage() {
    return this.message ? <Message key='message'>{this.message}</Message> : null
  }
  renderLoading(size) {
    return <Spinner size={size}/>
  }
  renderChildren() {return this.props.children}
  render() {
    return <div {...this.props.id ? {id: this.props.id} : {}} className={this.className} onClick={this.onClick}>{this.children}</div>
  }
}
