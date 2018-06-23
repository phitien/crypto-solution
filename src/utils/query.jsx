import querystring from 'querystring'
export function query(o) {
  if (typeof o != 'object') return o
  return querystring.stringify(o)
}
