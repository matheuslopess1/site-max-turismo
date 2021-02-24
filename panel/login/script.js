
const urlParams = new URLSearchParams(window.location.search)

if (urlParams.has('error')) {
    const containerElement = document.querySelector('#container')
    const error = urlParams.get('error')
    const div = document.createElement('div')
    div.classList.add('text-danger', 'text-center')
    div.innerText = error
    containerElement.appendChild(div)
}