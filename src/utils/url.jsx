export function url_is_absolute(root, o) {
  return o.startsWith('http') || o.startsWith('/') || o.startsWith(root)
}
export function url_normalize(root, o) {
  let uri = !o || o == '/' ? root : o.replace(/\{root\}/g, root || '')
  if (!url_is_absolute(root, uri)) uri = `${root}${uri}`
  return uri
}
export function baseurl(o) {
  return url_normalize(config.baseurl, o)
}
export function apiurl(o) {
  return url_normalize(config.apiurl, o)
}
export function url(o, query) {
  let uri = o || ''
  if (!query) return uri
  uri = uri.replace(/\W+$/g, '')
  query = (query || '').replace(/^\W+/g, '').replace(/\W+$/g, '');
  return uri.includes('?')
    ? `${uri}&${query}`
    : `${uri}?${query}`
}
