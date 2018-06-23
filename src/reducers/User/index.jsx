import {persitUser} from '../persit'

export default function(name, state, action, initialState) {
  switch (action.type) {
		case `${name}_ChangePassword_Pending`: {
      return {...state, loading: true}
    }
    case `${name}_ChangePassword_Success`: {
      return {...state, error: false, loading: false}
    }
    case `${name}_ChangePassword_Failure`: {
      return {...state, ...action.payload, loading: false}
    }

		case `${name}_Update_Pending`: {
      return {...state, loading: true}
    }
    case `${name}_Update_Success`: {
      state[name] = action.payload
      persitUser(state[name])
      return {...state, error: false, loading: false}
    }
    case `${name}_Update_Failure`: {
      return {...state, ...action.payload, loading: false}
    }

    case `${name}_Register_Pending`: {
      return {...state, loading: true}
    }
    case `${name}_Register_Success`: {
      return {...state, error: false, ...action.payload, loading: false}
    }
    case `${name}_Register_Failure`: {
      return {...state, ...action.payload, loading: false}
    }

    case `${name}_SignIn_Pending`: {
      return {...state, loading: true}
    }
    case `${name}_SignIn_Success`: {
      state[name] = action.payload
      persitUser(state[name])
      return {...state, error: false, loading: false}
    }
    case `${name}_SignIn_Failure`: {
      return {...state, ...action.payload, loading: false}
    }

    case `${name}_SignOut_Pending`: {
      persitUser({})
      return {...state, [name]: {}, error: false, loading: false}
    }
    case `${name}_SignOut_Success`: {
      return {...state, [name]: {}, error: false, loading: false}
    }
    case `${name}_SignOut_Failure`: {
      return {...state, [name]: {}, error: false, loading: false}
    }
  }
}
