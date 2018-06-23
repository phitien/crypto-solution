import assign from 'object-assign'

export function append(tagName, props) {
    if (document.getElementById(props.id)) return props.onload ? props.onload() : true
    props = Object.keys(props).reduce((rs, k) => {
        if (props[k]) rs[k] = props[k]
        return rs
    }, {})
    let tags = document.getElementsByTagName(tagName),
        el = document.createElement(tagName)
    assign(el, props)
    document.head.appendChild(el)
}
export function loadJs(src, id, onload, innerHTML) {
  append('script', {src, id, onload, innerHTML})
}
export function loadCss(href, id, onload) {
  append('link', {id, href, type: 'text/css', rel: 'stylesheet', onload})
}
export function loadStyle(style, id, onload) {
  append('style', {id, innerHTML: style, onload})
}
export function loadMeta(name, content, onload) {
  append('meta', {id: name, name, content, onload})
}
