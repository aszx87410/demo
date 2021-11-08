init()

function init() {
  const qs = Qs.parse(location.search.slice(1))
  titleInput.value = qs.title || ''
  contentInput.value = qs.content || ''
  if (!qs.styles) return

  const obj = {}
  const items = qs.styles.split('|||')
  for(let item of items) {
    let arr = item.split('@@@')
    if (!obj[arr[0]]) {
      obj[arr[0]] = {}
    }
    obj[arr[0]][arr[1]] = arr[2]
  }
  console.log(obj)
  
  updateStyle('titleInput', obj.title)
  updateStyle('contentInput', obj.content)
}

function updateStyle(id, styles) {
  const allowAttrs = [
    'color',
    'background',
    'font-weight',
    'font-size'
  ]

  // remove extra attributes
  for(let attr in styles) {
    if (!allowAttrs.includes(attr)) {
      delete styles[attr]
    }
  }

  // apply new styles
  let arr = []
  for(let attr in styles) {
    arr.push(`${attr}: ${escape(styles[attr])};`)
  }

  document.writeln(`
    <style>
      #${id} {
        ${arr.join('\n')}
      }
    </style>
  `)
  
}

titleColor.addEventListener('change',e => {
  titleInput.style.color = e.target.value
})

contentColor.addEventListener('change',e => {
  contentInput.style.color = e.target.value
})

save.onclick = e => {
  const title = titleInput.value
  const content = contentInput.value
  let arr = []
  let styles = []
  styles.push('title@@@color@@@' + getComputedStyle(titleInput).color)
  styles.push('content@@@color@@@' + getComputedStyle(contentInput).color)
  arr.push('title=' + encodeURIComponent(title))
  arr.push('content=' + encodeURIComponent(content))
  arr.push('styles=' + styles.join('|||'))

  alert('暫存成功！')

  location = location.pathname + `?${arr.join('&')}`
}

function escape(str) {
  return str && str.replace(/&/g, "&amp;")
   .replace(/</g, "&lt;")
   .replace(/>/g, "&gt;")
   .replace(/"/g, "&quot;")
   .replace(/'/g, "&#039;")
}