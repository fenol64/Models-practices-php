var containerElement, buttonElement, userElement, passElement;

containerElement = document.querySelector('.container');
userElement = document.getElementById('user_name');
passElement = document.getElementById('user_pass');
buttonElement = document.querySelector('#btn-send');

function addClass (type){
    containerElement.classList.add(type);
    setTimeout(() => {
        containerElement.classList.remove(type);
    }, 1000);
}

buttonElement.onclick = () => {
    let userInput, passInput;

    userInput = userElement.value;
    passInput = passElement.value;

    if (userInput === "" || passInput === ""){
        addClass("animationNo");
        document.querySelector('form').reset();
        return false;
    }else if (userInput === "admin" && passInput === "123") {
        addClass("animationgo");
        return true;
    }else{
        addClass("animationNo");
        document.querySelector('form').reset();
        return false;
    }
}


