// Obtener los parámetros de la barra de navegación de la URL actual
const queryParams = new URLSearchParams(window.location.search);
const qrCodeDiv = document.getElementById('qrCode');
// Extraer los valores de los parámetros
const year = queryParams.get('url').text();
qrCodeDiv.innerHTML = `<div style="background-image: URL(${qrDataURL});"><div>`;
