export default function(name, state, action, initialState) {
  switch (action.type) {
    case `${name}_Coins_Pending`: {
      state.Coins.loading = true
      return state
    }
    case `${name}_Coins_Success`: {
      state.Coins.list = action.payload
      state.Coins.loading = false
      return state
    }
    case `${name}_Coins_Failure`: {
      state.Coins.loading = false
      return state
    }
  }
}
