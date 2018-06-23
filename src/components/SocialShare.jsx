import React from 'react'

import Component from './Component'

export default class SocialShare extends Component {
  get cmpClassName() {return `social-share`}
  renderAddThis() {
    if (typeof addthis != 'undefined') addthis.toolbox('.addthis_toolbox')
  }
  componentDidMount() {
    super.componentDidMount()
    if (this.config.addthis) {
      if (typeof addthis == 'undefined') jQuery.getScript(this.config.addthis, this.renderAddThis.bind(this))
      else this.renderAddThis()
    }
  }
  componentDidUpdate(prevProps, prevState, snapshot) {
    super.componentDidUpdate(prevProps, prevState, snapshot)
    if (this.config.addthis) {
      if (typeof addthis == 'undefined') jQuery.getScript(this.config.addthis, this.renderAddThis.bind(this))
      else this.renderAddThis()
    }
  }
  renderFacebook(url) {
    return <i className='fa fa-facebook' onClick={e => {
      FB.ui({
        method: 'share',
        href: 'http://vnexpress.net',
      }, function(res) {
        console.log(res)
      })
    }}></i>
  }
  renderTwitter(url) {
    return <i className='fa fa-twitter' onClick={e => this.openNewTab(url)}></i>
  }
  renderLinkedin(url) {
    return <i className='fa fa-linkedin' onClick={e => this.openNewTab(url)}></i>
  }
  renderTelegram(url) {
    return <i className='fa fa-telegram' onClick={e => this.openNewTab(url)}></i>
  }
  renderChildren() {
    const items = ['facebook','twitter','linkedin','telegram','whatsapp','wechat']
    return <div className="addthis_inline_share_toolbox_0fd7 addthis_toolbox addthis_default_style addthis_32x32_style">
      {items.map(o => <a className={`addthis_button addthis_button_${o}`}></a>)}
    </div>
    const links = this.config.social
    return Object.keys(links).map(k => this[`render${k.ucfirst()}`] ? this[`render${k.ucfirst()}`](links[k]) : null).filter(o => o)
  }
}
