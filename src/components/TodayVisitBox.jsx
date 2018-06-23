import React from 'react'

import Component from './Component'

import {connect} from '../utils'

class TodayVisitBox extends Component {
  get cmpClassName() {return `box today-visit ${this.negative ? 'negative' : 'positive'}`}
  get data() {return this.props.data || {}}
  get total() {return this.data.total || this.props.total || 0}
  get live() {return this.data.live || this.props.live || 0}
  get percentage() {return this.data.percentage || this.props.percentage || 0}
  get negative() {return this.data.negative || this.props.negative}
  get today() {return (new Date()).format()}
  renderChildren() {
    return [
      <div className='box-title' dangerouslySetInnerHTML={{__html: `Today's Visits`}}/>,
      <div className='box-heading'>{this.total}</div>,
      <div className='box-content'>
        <div className='today'>{this.today}</div>
        <div className='live'>
          <h3 dangerouslySetInnerHTML={{__html: `Live`}}/>
          <span>{this.live}</span>
          <label dangerouslySetInnerHTML={{__html: `visitors right now`}} />
        </div>
      </div>,
      <div className='box-percentage-wrapper'>
        <div className='box-percentage-text' style={{left: `${this.percentage}%`}}>{this.percentage}</div>
        <div className='box-percentage' style={{width: `${this.percentage}%`}}></div>
      </div>,
    ]
  }
}
export default TodayVisitBox
