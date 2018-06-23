require('./src/prototypes')
var gulp = require('gulp')
var gutil = require('gulp-util')
var chalk = require('chalk')
var source = require('vinyl-source-stream')
var buffer = require('vinyl-buffer')
var browserify = require('browserify')
var babelify = require('babelify')
var uglify = require('gulp-uglify')
var minify = require('gulp-minify')
var sourcemaps = require('gulp-sourcemaps')
var clean = require('gulp-clean')
var sass = require('gulp-sass')
var autoprefixer = require('gulp-autoprefixer')
var concat = require('gulp-concat')
var inject = require('gulp-inject')
var rename = require('gulp-rename')
var replace = require('gulp-replace')
var connect = require('gulp-connect')
var livereload = require('gulp-livereload')
var socketio = require('socket.io')
var assign = require('object-assign')
var express = require('express')
var fs = require('fs')

const getArgvs = e => process.argv.slice(2).filter(i => i && i.trim() != '')
const argv = (name, dfVal) => {
  name = name ? name.trim() : ''
  var argvs = getArgvs()
  if (!name) return argvs
  var reg = new RegExp(`^-{1,2}\(${name}\)=(.*)\\s*$`)
  var boolReg = new RegExp(`^-{1,2}\(${name}\)\\s*$`)
  return argvs.reduce((rs,i) => {
    if (boolReg.test(i)) rs = true
    else {
      var matches = i.match(reg)
      if (matches) rs = matches[2]
    }
    return rs
  }, dfVal)
}

const addAllLibsToConfig = function(config) {
  config.gutil = gutil
  config.chalk = chalk
}
const polishConfig = function(config) {
  config.argv = argv
  config.profile = config.argv('profile', 'dev')
  config.port = config.argv('port', config.port || 2810)
  config.socket_port = config.argv('socket', config.socket_port)
  config.livereload = config.argv('livereload', config.livereload || 102884)
  config.debug = config.argv('debug') == '0' || config.argv('debug') == 'false' ? false : true

  config.trim = function(s) {return s.replace(/^\W/g, '').replace(/\W$/g, '').trim()}
  config.path = function(s) {return config.trim(s).replace(/_/g, '/')}
  config.files = function(dir, ext) {return [`${dir}/${ext || '*'}`,`${dir}/**/${ext || '*'}`]}
  config.log = config.debug ? config.gutil.log : function(...args) {}
  config.noop = config.gutil.noop
  config.ondata = function() {}
  config.onerror = function(...args) {
      config.log(...args)
      this.emit('end')
  }

  config.appname = config.argv('appname', config.appname)
  config.APPNAME = config.argv('APPNAME', config.APPNAME)
  config.AppName = config.argv('AppName', config.AppName)
  config.apptitle = config.argv('apptitle', config.apptitle || config.AppName)
  config.appdesc = config.argv('appdesc', config.appdesc || config.AppName)
  config.apppath = config.argv('apppath', config.apppath)
  config.baseurl = config.argv('baseurl', config.baseurl || '')
  config.buildversion = config.argv('buildversion', new Date().getTime())
  config.dest = config.argv('dest', './dest')
  config.dest_static = `${config.dest}/static`
  config.dest_js = `${config.dest_static}/js`
  config.dest_css = `${config.dest_static}/css`
  config.keywords = ['appname','AppName','APPNAME','apptitle','appdesc','appcolor','apppath','baseurl','version']

  config.libs = [
    ['redux', 'redux-thunk'],
    ['moment', 'when', 'axios', 'sync-request', 'uuid'],
    ['react', 'react-dom', 'react-router', 'react-redux', 'react-router-redux', 'react-cookies', 'react-table'],
  ].concat(config.libs || [])
}

var text = fs.readFileSync('./src/static/static/js/config.js', 'utf8')
eval(text)
addAllLibsToConfig(config)
polishConfig(config)
/**
 * Clean
 */
const cleanFn = function(config, cb) {
  config.log(config.chalk.blue('Running clean'))
  var exec = require('child_process').exec
  exec(`rm -rf ${config.dest}`, cb)
}
/**
 * config
 */
const configFn = function(config, cb) {
  config.log(config.chalk.blue('Running config'))
  var exec = require('child_process').exec
  exec(`echo "import {merge} from '../utils'" > src/config/index.jsx && echo "export default merge(config, require('./${config.profile}'))" >> src/config/index.jsx`, cb)
}
/**
 * Copy
 */
const copyFn = function(config, cb) {
  config.log(config.chalk.blue('Running copy'))
  gulp.src(config.files('./src/static'))
  .pipe(gulp.dest(config.dest, {overwrite: true}))
  .on('end', function() {
    gulp.src([
      './node_modules/react-table/react-table.css'
    ])
    .pipe(gulp.dest(`${config.dest_static}/react-table`, {overwrite: true}))
    .on('end', cb)
  })
}
/**
 * css
 */
const cssFn = function(config, cb) {
  config.log(config.chalk.blue('Running css'))
  gulp.src(`./src/scss/index.scss`)
      .pipe(config.debug ? sourcemaps.init() : config.noop())
      .pipe(sass({
          outputStyle: 'compressed',
          includePaths: ['.', './node_modules']
      })
      .on('error', sass.logError))
      .pipe(autoprefixer())
      .pipe(concat(config.debug ? `index.css` : `index-${config.buildversion}.css`))
      .pipe(config.debug ? sourcemaps.write('./') : config.noop())
      .pipe(replace('{baseurl}', config.baseurl))
      .pipe(gulp.dest(`${config.dest_css}/app`, {overwrite: true}))
      .on('end', cb)
}
/**
 * Vendor
 */
const vendorFn = function(config, cb, i) {
  i = i || 0
  if (i == 0) config.log(config.chalk.blue('Running vendor'))
  var bundleCnf = {
      debug: config.debug,
      transform: [babelify],
  }
  var bundler = browserify(bundleCnf)
  config.libs[i || 0].forEach(lib => bundler.require(lib))
  bundler.bundle()
  .pipe(source(`vendor-${i}.js`))
  .pipe(buffer())
  .pipe(config.debug ? sourcemaps.init() : config.noop())
  .pipe(config.debug ? config.noop() : uglify())
  .pipe(config.debug ? sourcemaps.write('./') : config.noop())
  .pipe(gulp.dest(`${config.dest_js}/libs`, {overwrite: true}))
  .on('error', function(err, ...args) {
    config.log(err, ...args)
    this.emit('end')
  })
  .on('end', function() {
    i++
    if (i == config.libs.length) {
      cb()
      return
    }
    vendorFn.bind(this, config, cb, i)()
  })
}
/**
 * js
 */
const jsFn = function(config, cb) {
  config.log(config.chalk.blue('Running js'))
  var bundleCnf = {
    debug: config.debug,
    transform: [babelify],
    entries: [`./src/index.jsx`],
    extensions: ['.jsx'],
    paths: ['.', './node_modules']
  }
  var bundler = browserify(bundleCnf)
  config.libs.forEach(libs => libs.forEach(lib => bundler.external(lib)))
  bundler.bundle()
  .on('update', jsFn)
  .on('error', function(err, ...args) {
      config.log(err, ...args)
      this.emit('end')
  })
  .pipe(source(config.debug ? `index.js` : `index-${config.buildversion}.js`))
  .pipe(buffer())
  .pipe(config.debug ? sourcemaps.init() : config.noop())
  .pipe(config.debug ? config.noop() : uglify())
  .pipe(config.debug ? sourcemaps.write('./') : config.noop())
  .pipe(gulp.dest(`${config.dest_js}/app`, {overwrite: true}))
  .on('end', cb)
}
/**
 * html
 */
const htmlFn = function(config, cb) {
  config.log(config.chalk.blue('Running html'))
  var baseurl = `${config.dest}/`
  var cssfiles = [].concat([].concat(config.css).filter(o => o).map(o => `${baseurl}${o.replace(`^\/g`, '')}`))
    .concat([
      `${config.dest_css}/*.css`,
      `${config.dest_css}/app/*.css`,
    ]),
    jsfiles = [].concat([].concat(config.js).filter(o => o).map(o => `${baseurl}${o.replace(`^\/g`, '')}`))
    .concat([
      `${config.dest_js}/*.js`,
      `${config.dest_js}/libs/*.js`,
      `${config.dest_js}/app/*.js`,
    ]).filter(o => o)
  var finish = function() {
    var bundle = gulp.src(`${config.dest}/index.html`)
    config.keywords.filter(o => o).map(o => bundle.pipe(replace(`{${o}}`, config[o] || '')))
    bundle
    .pipe(gulp.dest(config.dest, {overwrite: true}))
    config.routes.filter(o => o)
    .map(o => bundle.pipe(gulp.dest(`${config.dest}/${o}`, {overwrite: true})))
    bundle.on('end', cb)
  }
  if (config.debug) {
    var bundle = gulp.src(`./src/templates/index.html`)
    .pipe(replace('{meta}', [].concat(config.meta).filter(o => o).map(o => `<meta name="${o.name}" content="${o.content}"/>`).join('')))
    .pipe(replace('{favicon32}', `{baseurl}${config.favicon32.replace(`^\/g`, '')}`))
    .pipe(replace('{favicon192}', `{baseurl}${config.favicon192.replace(`^\/g`, '')}`))
    .pipe(inject(gulp.src([].concat(cssfiles, jsfiles)), {
        transform: function(file) {
          var filename = `.${file}`.replace(baseurl, '{baseurl}')
          return /\.css$/.test(file) ? `<link href="${filename}" rel="stylesheet"/>` :
          /\.js$/.test(file) ? `<script src="${filename}" defer="true"></script>` : ''
        }
    }))
    .pipe(gulp.dest(config.dest, {overwrite: true}))
    .on('end', finish)
  }
  else {
    gulp.src(jsfiles)
    .pipe(concat(`index-${config.buildversion}.js`))
    .pipe(minify({
      ext:{
          src:'-ezy.js',
          min:'.js'
      },
    }))
    .pipe(gulp.dest(config.dest_js, {overwrite: true}))
    .on('end', function() {
      var bundle = gulp.src(`./src/templates/index.html`)
      .pipe(replace('{meta}', [].concat(config.meta).filter(o => o).map(o => `<meta name="${o.name}" content="${o.content}"/>`).join('')))
      .pipe(replace('{favicon32}', `{baseurl}${config.favicon32.replace(`^\/g`, '')}`))
      .pipe(replace('{favicon192}', `{baseurl}${config.favicon192.replace(`^\/g`, '')}`))
      .pipe(inject(gulp.src([`${config.dest_js}/index-${config.buildversion}.js`].concat(cssfiles)), {
          transform: function(file) {
            var filename = `.${file}`.replace(baseurl, '{baseurl}')
            return /\.css$/.test(file) ? `<link href="${filename}" rel="stylesheet"/>` :
            /\.js$/.test(file) ? `<script src="${filename}" defer="true"></script>` : ''
          }
      }))
      .pipe(gulp.dest(config.dest, {overwrite: true}))
      .on('end', finish)
    })
  }
}
/**
 * watch
 */
const watchFn = function(config, cb) {
  config.log(config.chalk.blue('Running watch'))
  gulp.watch([].concat(config.files(`./src/scss`)), [`css`])
  gulp.watch([].concat(config.files(`./src`, '*.jsx')), [`js`])
  gulp.watch([].concat(config.files(`./src/templates`)), [`html`])
  gulp.watch([].concat(config.files(`./src/static`)), [`copy`])
  livereload.listen(config.livereload)
  gulp.watch([
      `${config.dest}/*.css`,
      `${config.dest}/**/*.css`,
      `${config.dest}/*.js`,
      `${config.dest}/**/*.js`,
      `${config.dest}/index.html`,
  ])
  .on('change', livereload.reload)
  cb()
}
/**
 * start
 */
const startFn = function(config, cb) {
  config.log(config.chalk.blue('Running start'))
  const sockets = new Map()
  const app = express()
  if (config.baseurl) app.use(config.baseurl, express.static(config.dest))
  const broadcastConnect = (socket) => socket.emit('connect', {title: 'connection required', message: `Please connect to the ezy system`})
  connect.server({
    name: `${config.profile}`,
    root: [`${config.dest}`],
    port: config.port,
    livereload: {port: config.livereload},
    fallback: `${config.dest}/index.html`,
    middleware: function(connect, opt) {return [app]},
    serverInit: server => {
      const io = socketio(server)
      io.on('connection', function(socket) {
        broadcastConnect(socket)
        Object.keys(config.ssocket || '').map(o => socket.on(o, data => config.ssocket[o](socket, data)))
      })
    }
  })
  cb()
}
/**
 * build
 */
const buildFn = function(config, cb) {
  cleanFn(config,
    configFn.bind(this, config,
      copyFn.bind(this, config,
        cssFn.bind(this, config,
          jsFn.bind(this, config,
            vendorFn.bind(this, config,
              htmlFn.bind(this, config, cb)
            , 0)
          )
        )
      )
    )
  )
}
/**
 * gulp tasks
 */
gulp.task(`clean`, function(cb) {cleanFn.bind(this, config, cb)()})
gulp.task(`config`, function(cb) {configFn.bind(this, config, cb)()})
gulp.task(`copy`, function(cb) {copyFn.bind(this, config, cb)()})
gulp.task(`css`, function(cb) {cssFn.bind(this, config, cb)()})
gulp.task(`js`, function(cb) {jsFn.bind(this, config, cb)()})
gulp.task(`vendor`, function(cb) {vendorFn.bind(this, config, cb, 0)()})
gulp.task(`html`, function(cb) {htmlFn.bind(this, config, cb)()})
gulp.task(`watch`, function(cb) {watchFn.bind(this, config, cb)()})
gulp.task(`start`, function(cb) {startFn.bind(this, config, cb)()})
gulp.task(`build`, function(cb) {buildFn.bind(this, config, cb)()})
gulp.task(`deploy`, function(cb) {
  config.log(config.chalk.blue('Running deploy'))
  var exec = require('child_process').exec
  var target = argv('target', 'root@45.117.83.14:/home/admin/domains/purecode.vn/private_html/dashboard')
  console.log(`Target: ${target}`)
  exec(`scp -r ${__dirname}/${config.dest}/* ${target}`, cb)
  // exec(`scp -r root@45.117.83.14:/home/admin/domains/purecode.vn/private_html/core3/libraries ${__dirname}/share/libraries`)
  exec(`scp -r ${__dirname}/share/* root@45.117.83.14:/home/admin/domains/purecode.vn/private_html/share`)
  exec(`scp -r ${__dirname}/api/* root@45.117.83.14:/home/admin/domains/purecode.vn/private_html/sozoapi`)
})
gulp.task(`default`, function(cb) {
  buildFn(config, startFn.bind(this, config, watchFn.bind(this, config, cb)))
})
