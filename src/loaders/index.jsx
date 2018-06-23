import {loadJs, loadCss, loadStyle, loadMeta} from '../utils'

export class Loader {
    constructor(obj, id, cb, t) {
        this.id = id
        this.obj = obj
        this.callback_name = `${(this.id || '').replace(/\W/g, '_')}_callback`
        this.event = `${(this.id || '').replace(/\W/g, '_')}_loaded`
        this.callback = cb
        this.time = t || 500
    }
    set(k, v) {
        this[k] = v
        return this
    }
    addCallback() {
        loadJs('', this.callback_name, null, `
            window.${this.callback_name} = function() {
                setTimeout(e => dispatchEvent(new CustomEvent('${this.event}')), ${this.time})
            }
        `)
    }
    load() {
        this.addCallback()
    }
}
export class JsLoader extends Loader {
    load() {
        super.load()
        loadJs(this.obj, this.id, this.callback || window[this.callback_name])
    }
}
export class CssLoader extends Loader {
    load() {
        super.load()
        loadCss(this.obj, this.id, this.callback || window[this.callback_name])
    }
}
export class StyleLoader extends Loader {
    load() {
        super.load()
        loadStyle(this.obj, this.id, this.callback || window[this.callback_name])
    }
}
export class MetaLoader extends Loader {
    load() {
        super.load()
        loadMeta(this.obj, this.id, this.callback || window[this.callback_name])
    }
}
