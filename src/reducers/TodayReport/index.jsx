export default function(name, state, action, initialState) {
  switch (action.type) {
    case `${name}_Order_Pending`: {
      state.Order.loading = true
      return state
    }
    case `${name}_Order_Success`: {
      state.Order = {...state.Order, ...action.payload, loaded: true}
      return state
    }
    case `${name}_Order_Failure`: {
      state.Order.loading = false
      return state
    }

    case `${name}_Visit_Pending`: {
      state.Visit.loading = true
      return state
    }
    case `${name}_Visit_Success`: {
      state.Visit = {...state.Visit, ...action.payload, loaded: true}
      return state
    }
    case `${name}_Visit_Failure`: {
      state.Visit.loading = false
      return state
    }
  }
}
