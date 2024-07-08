const { Client } = require('whatsapp-web.js');
const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  // Cargar el archivo HTML local
  await page.goto(`file://${__dirname}/public/content.html`);

  // Extraer datos del archivo HTML, por ejemplo:
  const title = await page.title();

  // Configurar cliente de WhatsApp Web
  const client = new Client();
  
  client.on('qr', (qr) => {
    // Mostrar QR en la consola o en una interfaz gráfica para iniciar sesión
    console.log(qr);
  });

  client.on('ready', () => {
    console.log('Cliente de WhatsApp listo!');
    // Ejemplo de enviar un mensaje
    client.sendMessage('Número de teléfono', `Hola desde Puppeteer! El título es: ${title}`);
  });

  client.initialize();

  await browser.close();
})();
