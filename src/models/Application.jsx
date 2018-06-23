const {api} = config

export const acts = {
  Menus: `api|get|true|body|${api.menus}`,
  SwitchForm: 'SwitchForm',
  Indicator: 'Indicator',
  AddModal: 'AddModal',
  RemoveModal: 'RemoveModal',
  AddMessage: 'AddMessage',
  RemoveMessage: 'RemoveMessage',
}
export const Menus = {}
export default {
  loading: false, error: false, persistent: false, loadmore: false,
  form: 'signin',
  modals: [],
  messages: [],
  acts,
  Menus,
}
