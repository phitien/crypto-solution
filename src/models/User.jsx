const {api} = config

export const acts = {
  User: `api|get|true|body|${api.profile}`,
  Update: `api|post|true|form|${api.profile_update}`,
  SignIn: `api|post|true|form|${api.signin}`,
  SignUp: `api|post|true|form|${api.signup}`,
  SignOut: `api|get|true|form|${api.signout}`,
  ForgotPassword: `api|get|true|form|${api.forget_password}`,
  ChangePassword: `api|post|true|form|${api.change_password}`,
}
export const User = {
  token: '',
  username: '',
  uid: '',
  phoneNumber: '',
  name: '',
  fname: '',
  lname: '',
  birthday: '',
  email: '',
  nationality: '',
  avatar: '',
  created_date: '',
  facebook: '',
  displayName: '',
  fullname: '',
  gender: '',
  documentType: '',
  document: '',
  verified: false,
}
export default {
  loading: false, error: false, persistent: true, loadmore: false,
  fields: Object.keys(User),
  acts,
  User,
}
