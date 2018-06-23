const {api} = config

export const acts = {
  Order: `api|get|true|body|${api.todayorder}`,
  Visit: `api|get|true|body|${api.todayvisit}`,
}
export const Order = {}
export const Visit = {}
export default {
  loading: false, error: false, persistent: false, loadmore: false,
  acts,
  Order,
  Visit,
}
