const container = document.getElementById('inputs')
const add = document.getElementById('add')

function verifyinputs() {
    let inputs = container.querySelectorAll(' .input_especialidade ')
    console.log(inputs)
    return inputs.length
}


add.onclick = () => {
    let i = verifyinputs() 
    let input = document.createElement("input")                     
    input.setAttribute('placeholder', 'Digite sua especialidade')
    input.setAttribute('name', `especialidade${i}`)     
    input.setAttribute('class', 'input_especialidade')                      
    container.appendChild(input) 
}