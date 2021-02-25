const tbodyEl = document.querySelector('tbody')
const selectOutgoingAccountEl = document.querySelector('select[name=outgoing_account]')
const selectIncomingAccountEl = document.querySelector('select[name=incoming_account]')
const inputAmountEl = document.querySelector('input[name=amount]')

const urlParams = new URLSearchParams(window.location.search);

for (const transfer of transfers) {
    const trEl = document.createElement('tr')
    tbodyEl.appendChild(trEl)
    const keys = Object.keys(transfer)
    transfer.amount = new Intl
        .NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' })
        .format(transfer.amount)
    const elements = new Map()
    for (const key of keys) {
        const tdEl = document.createElement('td')
        trEl.appendChild(tdEl)
        tdEl.innerText = transfer[key]
        elements.set(key, tdEl)
    }
    if (role === 'SUPERUSER' && transfer.madeBy === null) {
        const tdEl = elements.get('madeBy')
        const button = document.createElement('button')
        button.classList.add('btn', 'btn-sm', 'btn-success')
        button.innerText = 'Feito'
        button.addEventListener('click', (mouseEvent) => {
            const confirmed = confirm('Marcar como feito?')
            if (confirmed === false) return
            const formData = new FormData()
            formData.append('id', transfer.id)
        })
        tdEl.appendChild(button)
    }
}

for (const { id, shortName } of outgoingAccounts) {
    const option = document.createElement('option')
    option.value = id
    option.innerText = shortName
    selectOutgoingAccountEl.appendChild(option)
}

for (const { id, shortName } of incomingAccounts) {
    const option = document.createElement('option')
    option.value = id
    option.innerText = shortName
    selectIncomingAccountEl.appendChild(option)
}

selectOutgoingAccountEl.selectedIndex = -1
selectIncomingAccountEl.selectedIndex = -1

if (urlParams.has('error')) {
    const formEl = document.querySelector('form')
    const error = urlParams.get('error')
    const div = document.createElement('div')
    div.classList.add('text-danger', 'text-center', 'mt-3')
    div.innerText = error
    formEl.appendChild(div)
}