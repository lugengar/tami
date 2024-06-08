const qrForm = document.getElementById('qrForm');
const qrCodeDiv = document.getElementById('qrCode');
const contadorElemento = document.getElementById('contador');

let fincontador = true
qrForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    if(fincontador == true){
    fincontador = false;

        const formData = new FormData(qrForm);
            qrCodeDiv.innerHTML = ``;
            contadorElemento.textContent = `Generando codigo QR..`;
        
        try {
            const response = await fetch(`/generate-qr?text1=${encodeURIComponent(formData.get('nombre'))}&text2=${encodeURIComponent(formData.get('contraseña'))}`);
            const qrDataURL = await response.text();
            if(qrDataURL[0] == "d"){
                qrCodeDiv.innerHTML = `<div style="background-image: URL(${qrDataURL});"><div>`;
                iniciarContador();
            }else{
            qrCodeDiv.innerHTML = ``;
                contadorElemento.textContent = qrDataURL;
            fincontador = true;

            }
            
        } catch (error) {
            console.error('Error generando el código QR:', error);
            contadorElemento.textContent = 'Error generando el código QR';
            fincontador = true;

        }
    }
});

function iniciarContador() {
    let tiempoRestante = 50

    contadorElemento.textContent = tiempoRestante;

    const intervalo = setInterval(() => {
        tiempoRestante--;
        contadorElemento.textContent = tiempoRestante;

        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            qrCodeDiv.innerHTML = `<div>Codigo QR Expirado<div>`;
            contadorElemento.textContent = ""
            fincontador = true;
        }
    }, 1000);
}

