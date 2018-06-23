const lang = document.documentElement.lang

function timeDifference(d1, d2) {
  var obj = {}
  obj._milliseconds = d1.valueOf() - d2.valueOf()
  obj.milliseconds = obj._milliseconds % 1000
  obj._seconds = (obj._milliseconds - obj.milliseconds) / 1000
  obj.seconds = obj._seconds % 60
  obj._minutes = (obj._seconds - obj.seconds) / 60
  obj.minutes = obj._minutes % 60
  obj._hours = (obj._minutes - obj.minutes) / 60
  obj.hours = obj._hours % 24
  obj._days = (obj._hours - obj.hours) / 24
  obj.days = obj._days % 365
  // finally
  obj.years = (obj._days - obj.days) / 365
  return obj
}
function scrollTo(e,cb) {
  jQuery('html, body').animate({
      scrollTop: jQuery(e).offset().top
  }, 1000, cb)
}
function next(e,bg,cb) {
  if (e) {
    const toEl = e.closest('.section').nextElementSibling, jToEl = jQuery(toEl)
    if (bg) jToEl.css('background-color', bg)
    scrollTo(toEl, function(e) {
      if (cb) cb(toEl)
    })
  }
}
function closePopup(cb) {
  jQuery('.overlay').remove()
  if (typeof cb == 'function') cb()
}
function showPopup(html, classes, close, closeFn, cb) {
  closePopup(function() {
    jQuery('body').append(`<div class="overlay ${classes || ''}">
      ${html}
      ${close ? `<i class="material-icons close-icon">clear</i>` : ''}
    </div>`)
    jQuery('.overlay .close-icon').on('click', closeFn || closePopup)
    if (cb) cb()
  })
}
function showIframe(url, cb) {
  let html = `<iframe src="${url}" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>`
  showPopup(html, 'iframe-popup', true, undefined, cb)
}
function formSubmit(html) {
  jQuery('body').append(`<div id="temp">${html}</div>`)
  if (jQuery('#temp').find('form')) {
    jQuery('#temp').find('form').submit()
  }
}
function mailchimpCallback(win) {
  setTimeout(e => {
    closePopup()
  }, 500)
}
function switchLang(lang) {
  lang = lang == 'en' ? '' : `/${lang}`
  let pieces = location.pathname.replace('index.html', '').replace(/\/*^/g, '').split('/').filter(o => o)
  pieces.shift()
  let url = `${pieces.join('/')}${lang}/index.html${location.hash}`
  location.href = url
}
function show(o, duration, cb) {
  o.show('slide', { direction: 'left' }, duration, cb)
}
function hide(o, duration, cb) {
  o.hide('slide', { direction: 'left' }, duration, cb)
}
