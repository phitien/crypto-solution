import React from 'react'
import ReactDOM from 'react-dom'
import {Provider} from 'react-redux'
import {ConnectedRouter} from 'react-router-redux'
import {Switch} from 'react-router'

import {actions} from '../actions'
import {store} from '../store'
import {routes} from '../routes'
import {CssLoader, JsLoader, MetaLoader} from '../loaders'
import {history} from '../utils'
import {emitter} from '../emitter'

export const regCmps = new Map()

export class Application {
  get regCmps() {return regCmps}
  get container() {return document.getElementById(this.__container || config.container || 'application')}
  set container(v) {this.__container = v}
  get store() {return this.__store || store}
  set store(v) {this.__store = v}
  get routes() {return this.__routes || routes}
  set routes(v) {this.__routes = v}

  facebook_sdk_init = e => {
    if (!config.facebook) return
    const [cb, t] = e ? e.detail || [] : []
    new JsLoader(
      `//connect.facebook.net/en_US/sdk.js`,
      'facebook-sdk', cb, t).set('callback_name', 'fbAsyncInit').load()
  }
  google_platform_init = e => {
    if (!config.google || !config.google.clientId) return
    const [cb, t] = e ? e.detail || [] : []
    new MetaLoader(config.google.clientId, 'google-signin-client_id').load()
    new JsLoader(
      `//apis.google.com/js/client:platform.js?onload=google_platform_loaded`,
      'google-platform', cb, t).load()
  }
  google_maps_api_init = e => {
    if (!config.google || !config.google.apiKey) return
    const [cb, t] = e ? e.detail || [] : []
    new JsLoader(
      `//maps.googleapis.com/maps/api/js?key=${config.google.apiKey}&libraries=places&callback=google_maps_api_callback`,
      'google-maps-api', cb, t).load()
  }
  unload = e => {
    Object.keys(this.events.app).map(o => this.events.app[o].remove())
    Object.keys(this.events.window).map(o => window.removeEventListener(o, this.events.window[o]))
  }
  refresh = e => this.render()
  resize = e => this.refresh()
  cmp_mounted = cmp => this.regCmps.set(cmp.cmpId, cmp)
  cmp_unmounted = cmp => this.regCmps.delete(cmp.cmpId)
  beforeRender = e => {
    this.facebook_sdk_init()
    this.google_platform_init()
    this.google_maps_api_init()
  }
  afterRender = e => {
    if (config.theme) jQuery('body').addClass(config.theme)
    this.events = {
      app: {
        unload: emitter.addListener('unload', this.unload),
        // resize: emitter.addListener('resize', this.resize),
        refresh: emitter.addListener('refresh', this.refresh),
        cmp_mounted: emitter.addListener('cmp_mounted', this.cmp_mounted),
        cmp_unmounted: emitter.addListener('cmp_unmounted', this.cmp_unmounted),
      },
      window: {
        unload: (...args) => emitter.emit('unload', ...args),
        resize: (...args) => emitter.emit('resize', ...args)
      }
    }
    Object.keys(this.events.window).map(o => window.addEventListener(o, this.events.window[o]))
  }
  render() {
    this.beforeRender()
    if (!this.store) {
      throw `No store provided`
      return
    }
    if (!this.routes) {
      throw `No routes provided`
      return
    }
    if (!this.container) {
      throw `No container provided`
      return
    }
    ReactDOM.render(
      <Provider store={this.store}>
        <ConnectedRouter history={history}>
          <Switch>{this.routes}</Switch>
        </ConnectedRouter>
      </Provider>
      , this.container
      , this.afterRender
    )
  }
  dispatch() {
    this.render()
  }
}
