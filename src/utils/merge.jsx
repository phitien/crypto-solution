import assign from 'object-assign'
export function isObject(o) {
  if (!o) return false
  if (Array.isArray(o)) return false
  return typeof o == 'object'
}
export function merge(target, o) {
  if (!isObject(target)) return target = o
  if (!isObject(o)) return target
  if (!target) target = {}
  const keys = Array.from(new Set([].concat(Object.keys(target)).concat(Object.keys(o)).filter(o => o)))
  target = keys.reduce((rs, k) => {
    let v = o[k]
    if (v !== undefined) rs[k] = !isObject(v) ? v : assign({}, rs[k], v)
    return rs
  }, target)
  return target
}
