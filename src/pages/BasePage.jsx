import React from 'react'

import {Page, Menu, Header, Space, Introduction, Logo, UserBox, SignInSignUp} from '../components'
import {Left} from '../components'

export class BasePage extends Page {
  initialState() {return this.state = {
    ...super.initialState(),
    html: null
  }}
  get private() {return false}
  get html() {return null}

  componentDidMount() {
    super.componentDidMount()
    this.actions.User_Load(JSON.parse(localStorage.getItem(`User`)))
    if (this.html) this.utils.apiCall.get(this.utils.baseurl(`static/html/${this.html}`))
    .then(res => this.setState({html: res.data}))
  }
  // renderTopHeader() {return <div className='top-header'></div>}
  renderHeader() {return <Header>
    <Logo/>
    <Space/>
    <Menu className='top' items={this.props.Application.Menus.top}/>
    <UserBox/>
  </Header>}
  renderLeft() {return <Left ref={e => this.left = e} items={this.props.Application.Menus.left}/>}
  renderMain() {
    return <div ref={e => this.main = e} className='main'>
      {this.renderStaticHtml()}
      {!this.private || this.logged ? this.renderMainContent() : this.renderMainContentAnonymous()}
    </div>
  }
  renderStaticHtml() {return this.state.html ? <div className='static-html' dangerouslySetInnerHTML={{__html: this.state.html}}></div> : null}
  renderMainContent() {return null}
  renderMainContentAnonymous() {return <div className='not-logged'>
    <Introduction/>
    <SignInSignUp/>
  </div>}
  renderRight() {return <div ref={e => this.right = e} className='right'><div className='wrapper'>{this.renderRightContent()}</div></div>}
  renderRightContent() {return null}
  renderPageComponents() {
    return [
      this.renderLeft(),
      this.renderMain(),
      this.renderRight(),
    ]
  }
}
