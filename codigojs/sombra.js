opciones = document.querySelectorAll('.opciones');
opcionesx = document.querySelectorAll('.equis');
sombra = document.getElementById("sombra")
var click = true
var som = false
function blur() {
    if(click == true){
        sombra.style.display="grid"
        sombra.style.animation = "sombra both 0.5s";
    }
}
sombra.addEventListener('animationend', function handleAnimationEnd() {
    if(som == true){
        som = false
        sombra.style.display="none"
    }else{
        som = true
    }
    click = true
    
});
function sacarblur() {
    if(click == true){
        click = false
        sombra.style.animation = "sacarsombra both 0.5s";
        
    }
    
}


opciones.forEach(element => {
    element.addEventListener('click', blur);
});
opcionesx.forEach(element => {
    element.parentNode.addEventListener('click', sacarblur);
});
