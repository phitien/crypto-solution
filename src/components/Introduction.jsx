import React from 'react'
import Component from './Component'
import Logo from './Logo'

export default class Introduction extends Component {
  get cmpClassName() {return 'introduction'}
  renderChildren() {return [
    // <Logo className='small'/>,
    <div className='text' dangerouslySetInnerHTML={{__html: `Single account<br/> and one-stop KYC for all ICOs`}}></div>
  ]}
}
