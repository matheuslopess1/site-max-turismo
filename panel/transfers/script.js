const tbodyEl = document.querySelector('tbody')
const selectOutgoingAccountEl = document.querySelector('select[name=outgoing_account]')
const selectIncomingAccountEl = document.querySelector('select[name=incoming_account]')
const inputAmountEl = document.querySelector('input[name=amount]')

for (const transfer of transfers) {
    const trEl = document.createElement('tr')
    tbodyEl.appendChild(trEl)
    const keys = Object.keys(transfer)
    for (const key of keys) {
        const tdEl = document.createElement('td')
        trEl.appendChild(tdEl)
        tdEl.innerText = transfer[key]
    }
}

for (const { id, name, type, bank, agency, account } of outgoingAccounts) {
    const option = document.createElement('option')
    option.value = id
    option.innerText = `${name} ${type} ${bank}/${agency}/${account}`
    selectOutgoingAccountEl.appendChild(option)
}

for (const { id, name, type, bank, agency, account } of incomingAccounts) {
    const option = document.createElement('option')
    option.value = id
    option.innerText = `${name} ${type} ${bank}/${agency}/${account}`
    selectIncomingAccountEl.appendChild(option)
}