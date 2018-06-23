import React from 'react'

import {Form, Input, Button} from '../components'
import {BasePage} from './BasePage'

export class WalletPage extends BasePage {
  componentDidMount() {
    super.componentDidMount()
    this.actions.Wallet_Coins()
  }
  get cmpId() {return 'page-wallet'}
  get html() {return 'wallet.html'}
  get items() {return [].concat(this.props.Wallet.Coins.list).filter(o => o)}
  renderMainContent() {
    return <Form className='inline wallet-items'>
    {this.items.map(o => <div className='wallet-item' data-code={o.code} data-name={o.name}>
      <div className='wallet-item-amount' data-code={o.code} data-name={o.name}>
        {o.amount}
        <span className='wallet-item-usd'>{o.usd}</span>
      </div>
    </div>)}
  </Form>}
}
