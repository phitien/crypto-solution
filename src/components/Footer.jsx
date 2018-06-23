import React from 'react'

import Component from './Component'
import Logo from './Logo'
import Space from './Space'
import Copyright from './Copyright'
import SocialShare from './SocialShare'
import ContactUs from './ContactUs'

import {connect} from '../utils'

class Footer extends Component {
  get cmpClassName() {return 'footer'}
  renderChildren() {return [
    <Logo className='small'/>,
    <Copyright/>,
    <div className='contact-us-link' onClick={e => this.actions.Application_AddModal({header: 'Contact Us', content: <ContactUs/>})}>Contact Us</div>,
    <a className='mailto-link' href={`mailto://${this.config.emails.support}`}>E-mail us</a>,
    <Space/>,
    <SocialShare/>,
  ]}
}
export default connect(Footer)
