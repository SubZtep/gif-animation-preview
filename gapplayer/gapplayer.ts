const tpl = document.createElement("div")
tpl.className = "gapplayer"
tpl.style.display = "inline-block"
tpl.style.border = "2px solid black"
tpl.style.padding = "8px"
tpl.textContent = "GIF"

Array.from(document.querySelectorAll(".gap")).forEach(el => {
  el.replaceWith(tpl.cloneNode(true))
})
