import React from 'react'
import {Route} from 'react-router'
import pages from '../pages'

const apppath = `${(config.apppath || '').trim().replace(/\/*$/g, '')}/`

export const routes = [{path: `${apppath}`, component: pages.HomePage}]
.concat(
  Object.keys(pages).filter(o => o && o != 'HomePage')
  .map(o => ({path: `${apppath}${o.replace('Page', '').toLowerCase()}`, component: pages[o]}))
)
.map((r,i) => <Route key={i} exact {...r}/>)
