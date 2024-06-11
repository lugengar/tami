
opcionequis2 = document.getElementById("opcionequis2")
user = document.getElementById("user")
sombra2 = document.getElementById("sombra2")

var click = true
var som2 = false
function blur2() {
    if(click == true){
        sombra2.style.display="grid"
        sombra2.style.animation = "sombra both 0.5s";
    }
}
sombra2.addEventListener('animationend', function handleAnimationEnd() {
    if(som2 == true){
        som2 = false
        sombra2.style.display="none"
    }else{
        som2 = true
    }
    click = true
    
});
function sacarblur2() {
    if(click == true){
        click = false
        sombra2.style.animation = "sacarsombra both 0.5s";
        
    }
    
}

user.addEventListener('click', blur2);

opcionequis2.addEventListener('click', sacarblur2);
