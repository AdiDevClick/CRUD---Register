
/* const items = document.querySelectorAll(".box")
let offsetX, offsetY

items.forEach(item => {
    const move = (event) => {
        // Update item position based on new cursor position
        item.style.left = `${event.clientX - offsetX}px`
        item.style.top = `${event.clientY - offsetY}px`
    }

    item.addEventListener("mousedown", (event) => {
        // Calculate the initial offset values
        offsetX = event.clientX - item.offsetLeft
        offsetY = event.clientY - item.offsetTop
        document.addEventListener("mousemove", move)
    })
    // Remove mousemove event listener on mouse up event
    document.addEventListener("mouseup", () => {
        document.removeEventListener("mousemove", move)
    })    
}) */

document.addEventListener('DOMContentLoaded', (e) => {

    function handleDragStart(e) {
        this.style.opacity = '0.4';
      
        dragSrcEl = this;
      
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
    }
  
    function handleDragEnd(e) {
        this.style.opacity = '1'
    
        items.forEach(item => {
            item.classList.remove('over')
      })
    }
  
    function handleDragOver(e) {
        e.preventDefault()
      return false
    }
  
    function handleDragEnter(e) {
        this.classList.add('over')
    }

    function handleDragLeave(e) {
        this.classList.remove('over')
    }

    function handleDrop(e) {
        e.stopPropagation();
      
        if (dragSrcEl !== this) {
          dragSrcEl.innerHTML = this.innerHTML;
          this.innerHTML = e.dataTransfer.getData('text/html');
        }
      
        return false;
    }

    /* function move (event) {
        let offsetX, offsetY
        // Update item position based on new cursor position
        this.style.left = `${event.clientX - offsetX}px`
        this.style.top = `${event.clientY - offsetY}px`
    }
    
    function mouseDown(e) {
        // Calculate the initial offset values
        offsetX = e.clientX - this.offsetLeft
        offsetY = e.clientY - this.offsetTop
    }
        
    function mouseUp(e) {
        // Remove mousemove event listener on mouse up event
        this.removeEventListener("mousemove", move)
    } */
  
    let items = document.querySelectorAll(".box")
    items.forEach(item => {
        item.addEventListener('dragstart', handleDragStart)
        item.addEventListener('dragover', handleDragOver)
        item.addEventListener('dragenter', handleDragEnter)
        item.addEventListener('dragleave', handleDragLeave) 
        item.addEventListener('dragend', handleDragEnd)
        item.addEventListener('drop', handleDrop)
        /* item.addEventListener('mousedown', mouseDown)
        item.addEventListener('mousemove', move)
        item.addEventListener('mouseup', mouseUp) */

    })
  })