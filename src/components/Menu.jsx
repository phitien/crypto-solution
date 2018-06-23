import React from 'react'
import {Link} from 'react-router-dom'
import Component from './Component'

const trim = s => (typeof s == 'string' ? s : '').trim().replace(/^\//g, '').replace(/\/$/g, '')

export default class Menu extends Component {
  get className() {return `menu menu${this.level} ${this.level == 0 && this.props.className || ''}`}
  get level() {return this.props.level || 0}
  get root() {return `${this.props.root || ''}/`}
  get items() {return [].concat(this.props.items).filter(o => o)}

  nodeUrl = n => this.utils.url(this.utils.baseurl(trim(`${this.root}${n.url || ''}`)))
  nodeClassName = n => {
    const url = this.nodeUrl(n),
      path = trim(location.pathname)
    n.active = url == location.href || [url].concat(n.active).filter(o => o).reduce((rs, o) => {
      if (!rs) rs = path == o || path == trim(o)
      return rs
    }, false)
    const rs = [
      n.className,
      n.private ? 'private' : 'public',
      `node node${this.level}`,
      n.active ? 'active' : null,
      this.hasChildren(n) ? 'has-children' : null,
    ]
    return rs.filter(o => o).join(' ')
  }
  hasChildren = n => n.children && n.children.length
  nodeClick = (e, n) => {
    if (n.onClick) return n.onClick(e)
    return this.open(this.nodeUrl(n))
  }

  renderMenu(items, className) {
    items = [].concat(items).filter(n => n)
    if (!items.length) return null
    return <ul className={this.className}>{items.map((n, i) => {
      const title = n.title || n.name || n.label
      const description = n.description
      const image = !n.image ? null : /<\/?[^>]*>/.test(n.image) ? n.image : `<img src="${n.image}" alt="${title}"/>`
      return n.html ?
      <li key={i} className={this.nodeClassName(n)}>{n.html}</li> :
      <li key={i} className={this.nodeClassName(n)}>
        <div className='name' onClick={e => this.nodeClick(e, n)}>
          {!image ? null :
          <div className='image' dangerouslySetInnerHTML={{__html: image}}/>}
          <span>{title}</span>
        </div>
        {!description ? null :
        <div className='description'>{description}</div>}
        <Menu items={n.children} level={this.level + 1}/>
      </li>
      })}
    </ul>
  }
  render() {
    return this.renderMenu(this.items, this.className)
  }
}
