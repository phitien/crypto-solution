export function include(o, ...args) {
  if (!args.length) return o
  return Object.keys(o).reduce((rs, k) => {
    args.includes(k) ? rs[k] = o[k] : false
    return rs
  }, {})
}
