export default function(name, state, action, initialState) {
  const subname = 'Menus', props = `${name}_${subname}`
  switch (action.type) {
    case `${name}_Indicator`: {
      state.loading = action.payload
      return state
    }

    case `${name}_SwitchForm`: {
      state.form = action.payload
      return state
    }
    case `${name}_AddModal`: {
      state.modals.push(action.payload)
      return state
    }
    case `${name}_RemoveModal`: {
      const i = state.modals.indexOf(action.payload)
      if (i >= 0) state.modals.splice(i, 1)
      return state
    }

    case `${name}_AddMessage`: {
      state.messages.push(action.payload)
      return state
    }
    case `${name}_RemoveMessage`: {
      const i = state.messages.indexOf(action.payload)
      if (i >= 0) state.messages.splice(i, 1)
      return state
    }

    case `${props}_Pending`: {
      return {...state, loading: true}
    }
    case `${props}_Success`: {
      state[subname] = {...state[subname], ...action.payload}
      state[subname].loaded = true
      return {...state, loading: false}
    }
    case `${props}_Failure`: {
      return {...state, loading: false}
    }
  }
}
