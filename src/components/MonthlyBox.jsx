import React from 'react'

import Component from './Component'

import {connect} from '../utils'

class MonthlyBox extends Component {
  get cmpClassName() {return `box monthly ${this.negative ? 'negative' : 'positive'}`}
  get data() {return this.props.data || {}}
  get coins() {return this.data.coins || this.props.coins || {}}
  get sale() {return this.data.sale || this.props.sale || 0}
  get currency() {return this.data.currency || this.props.currency || 'USD'}
  get percentage() {return this.data.percentage || this.props.percentage || 0}
  get negative() {return this.data.negative || this.props.negative}
  renderChildren() {
    return [
      <div className='box-title' dangerouslySetInnerHTML={{__html: 'Monthly Sale'}}/>,
      <div className='box-heading'>{this.sale} {this.currency}</div>,
      <div className='box-content'>
        <div className='coins'>{Object.keys(this.coins).map(o => <div className='coin'>
            <div className='coin-name'>{o}</div>
            <div className='coin-sale'>{this.coins[o].format()}</div>
          </div>)}
        </div>
      </div>,
      <div className='box-percentage-wrapper'>
        <div className='box-percentage-text' style={{left: `${this.percentage}%`}}>{this.percentage}</div>
        <div className='box-percentage' style={{width: `${this.percentage}%`}}></div>
      </div>,
    ]
  }
}
export default MonthlyBox
