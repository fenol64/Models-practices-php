const container = document.getElementById('inputs')
const add = document.getElementById('add')
const send = document.getElementById('send')

function verifyinputs() {

    let inputs = {
        "texts": container.querySelectorAll(' .text_especialidade ').length,
        "prices": container.querySelectorAll(' .price_especialidade ').length
    }

    return inputs
}

add.onclick = () => {
    let i = verifyinputs() 

    let text = document.createElement("input")     
    text.setAttribute('type', 'text')                
    text.setAttribute('placeholder', 'Digite sua especialidade')
    text.setAttribute('name', `especialidade${i["texts"]}`)     
    text.setAttribute('class', 'text_especialidade')                      
    container.appendChild(text) 

    let price = document.createElement("input")     
    price.setAttribute('type', 'number')                
    price.setAttribute('name', `price${i["prices"]}`)     
    price.setAttribute('class', 'price_especialidade')                      
    container.appendChild(price) 

}

send.onclick = () => {

    let state = {
        texts: Array.from(container.querySelectorAll(' .text_especialidade ')),
        prices: Array.from(container.querySelectorAll(' .price_especialidade ')),
        data: []
    }

    state.texts.map(text => {
        state.prices.map(price => {
            state.data.push({
                "especialidade": text.value, 
                "preco": price.value
            })
        })
    })

    $.post('./recebe.php', { "data": state.data }, res => {
        console.log(res)
    })

}
