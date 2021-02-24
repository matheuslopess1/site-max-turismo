const tbodyEl = document.querySelector('tbody')
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