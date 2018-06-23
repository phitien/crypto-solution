import React from 'react'

import Component from './Component'
import Message from './Message'
import Header from './Header'
import Content from './Content'
import Footer from './Footer'
import Modal from './Modal'

export default class Page extends Component {
  get cmpClassName() {return `container page ${this.logged ? 'logged' : 'not-logged'} ${this.User.verified ? 'user-verified' : 'user-not-verified'}`}
  get loading() {return this.props.Application.loading}
  componentDidMount() {
    super.componentDidMount()
    addEventListener('UserSignedIn', ({detail: [user]}) => this.actions.User_Load(user))
    addEventListener('UserSignedOut', (e) => this.actions.User_Unload())
  }
  renderPageComponents() {return null}
  renderTopHeader() {return null}
  renderHeader() {return <Header/>}
  renderContent() {return <Content>{this.renderPageComponents()}</Content>}
  renderFooter() {return <Footer/>}
  renderModals() {
    const modals = [].concat(this.props.Application.modals).filter(o => o)
    if (!modals.length) return null
    return modals.map(o => <Modal data={o} header={o.header} footer={o.footer}>{o.content}</Modal>)
  }
  renderMessages() {
    const messages = [].concat(this.props.Application.messages).filter(o => o)
    if (!messages.length) return null
    return <div className='messages'>{messages.map(o => <Message data={o}/>)}</div>
  }
  renderPageLoading() {
    return this.loading ? <div className='m-overlay'>{this.renderLoading()}</div> : null
  }
  renderChildren() {return [
    this.renderTopHeader(),
    this.renderHeader(),
    this.renderContent(),
    this.renderFooter(),
    this.renderPageLoading(),
    this.renderModals(),
    this.renderMessages(),
  ]}
}
