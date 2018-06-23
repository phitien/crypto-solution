const {api} = config

export const acts = {
  MonthlyReport: `api|get|true|body|${api.monthly}`,
}
export const MonthlyReport = {}
export default {
  loading: false, error: false, persistent: false, loadmore: false,
  acts,
  MonthlyReport,
}
