const qrForm = document.getElementById('qrForm');
const qrCodeDiv = document.getElementById('qrCode');

qrForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const formData = new FormData(qrForm);

    try {
        const response = await fetch(`/generate-qr?text1=${encodeURIComponent(formData.get('nombre'))}&text2=${encodeURIComponent(formData.get('contraseña'))}`);
        const qrDataURL = await response.text();
        qrCodeDiv.innerHTML = `<div style="background-image: URL(${qrDataURL});"><div>`;
    } catch (error) {
        console.error('Error generando el código QR:', error);
        qrCodeDiv.textContent = 'Error generando el código QR';
    }
});
