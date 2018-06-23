const {api} = config

export const acts = {
  Coins: `api|get|true|body|${api.wallet}`,
}
export const Coins = {list: [], filter: {}, loading: false}
export default {
  loading: false, error: false, persistent: false, loadmore: false,
  acts,
  Coins,
}
