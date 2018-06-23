import React from 'react'

import Component from './Component'

const getCName = o => o.name || o.title || o.label || o.heading
const getCField = o => o.field
const data = (f,r) => r[f] || ''
const getCText = (o, r) => {
  let rs = '', f = getCField(o)
  if (o.text) {
    try {eval(`rs = ${o.text}`)} catch(e) {}
  }
  else if (f) rs = r[f]
  return rs
}
const getCTitle = (o, r) => {
  let rs = '', f = getCField(o)
  if (o.title) {
    try {eval(`rs = ${o.title}`)} catch(e) {}
  }
  else if (f) rs = r[f]
  return rs
}

export default class Table extends Component {
  initialState() {return this.state = {
    ...super.initialState(),
    colums: [].concat(this.props.colums).filter(o => o),
    rows: [].concat(this.props.rows).filter(o => o),
    settings: this.props.settings || {},
  }}
  get cmpClassName() {return 'table'}
  get colums() {return [].concat(this.state.colums).filter(o => o)}
  set colums(v) {this.setAttr('colums', v)}
  get rows() {return [].concat(this.state.rows).filter(o => o)}
  set rows(v) {this.setAttr('rows', v)}
  get settings() {return this.state.settings}
  set settings(v) {this.setAttr('settings', {...this.state.settings, ...v})}
  get apiSuccess() {
    return data => {
      if (data.hasOwnProperty('settings')) this.settings = data.settings
      if (data.hasOwnProperty('colums')) this.colums = data.colums
      if (data.hasOwnProperty('rows')) this.rows = data.rows
    }
  }
  renderHeaderCell(o) {
    return <div className='cell header-cell' dangerouslySetInnerHTML={{__html: getCName(o)}}></div>
  }
  renderHeader() {
    const cols = this.colums.filter(o => !o.hidden)
    if (!cols.length) return null
    return <div className='table-header'><table><tbody><tr>
      {cols.map(o => <td>{this.renderHeaderCell(o)}</td>)}
    </tr></tbody></table></div>
  }
  renderCell(o, r) {
    return <div className='cell table-cell' title={getCTitle(o,r)} dangerouslySetInnerHTML={{__html: getCText(o,r)}}></div>
  }
  renderRow(r) {
    const cols = this.colums.filter(o => !o.hidden)
    if (!cols.length) return null
    return <tr>
      {cols.map(o => <td>{this.renderCell(o, r)}</td>)}
    </tr>
  }
  renderBody() {
    const cols = this.colums.filter(o => !o.hidden)
    if (!cols.length) return null
    const rows = this.rows.filter(o => !o.hidden)
    return <div className='table-body'><table><tbody>
      {rows.map(r => this.renderRow(r))}
    </tbody></table></div>
  }
  renderFooter() {
    return null
  }
  // renderChildren() {return <div className='table-container'>
  //   {this.renderHeader()}
  //   {this.renderBody()}
  //   {this.renderFooter()}
  // </div>}
  renderChildren() {
    const cols = this.colums.filter(o => !o.hidden)
    if (!cols.length) return null
    const rows = this.rows.filter(o => !o.hidden)
    return <div className='table-container'><table>
      <thead><tr>{cols.map(o => <td>{this.renderHeaderCell(o)}</td>)}</tr></thead>
      <tbody>{rows.map(r => this.renderRow(r))}</tbody>
    </table></div>}
}
