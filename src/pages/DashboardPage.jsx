import React from 'react'

import {Form, Input, InputCountry, InputDate, Button} from '../components'
import {BasePage} from './BasePage'

import {MonthlyBox, TodayOrderBox, TodayVisitBox} from '../components'
import {PieChart, LineChart} from '../components'

export class DashboardPage extends BasePage {
  componentDidMount() {
    super.componentDidMount()
    this.actions.MonthlyReport_MonthlyReport()
    this.actions.TodayReport_Order()
    this.actions.TodayReport_Visit()
  }
  get cmpId() {return 'page-dashboard'}
  get private() {return true}
  renderMainContent() {return <Form className='inline'>
    <div className='boxes'>
      <MonthlyBox data={this.props.MonthlyReport.MonthlyReport}/>
      <TodayOrderBox data={this.props.TodayReport.Order}/>
      <TodayVisitBox data={this.props.TodayReport.Visit}/>
    </div>
    <div className='boxes'>
      <PieChart id='piechart'/>
      <LineChart id='linechart' className='flex1 right' height={300} ref={e => this.statisticLineChart = e} api={this.config.api.statistic}
        apiSuccess={data => this.statisticLineChart.data = data}/>
    </div>
  </Form>}
}
