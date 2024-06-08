const express = require('express');
const qrcode = require('qrcode');
let mysql = require("mysql");

const { Client } = require('whatsapp-web.js');
const basededatos = require("./basededatos.js")
const chatbot = require("./chatbot.js")
const app = express();
const PORT = 3000;
const HOST = '192.168.100.6';

// Configurar Express para servir archivos estáticos
app.use(express.static('public'));

// Ruta de inicio para servir el archivo HTML estático
app.get('/', (req, res) => {
    res.sendFile(__dirname + '/index.html');
});


// Ruta para generar el código QR
app.get('/generate-qr', (req, res) => {
    const connection = mysql.createConnection({
        host: 'localhost',
        user: 'root',
        password: '',
        database: 'tami'
    });
    connection.connect(function(err) {
        if (err) {
            throw err;
        } else {
            console.log("Conexión exitosa a la base de datos");
            return connection;
        }
    });
    connection.query("SELECT * FROM chatbot WHERE chatbot.nombre = (?) AND chatbot.contraseña = (?)",[req.query.text1,req.query.text2], (error, results, fields) => {
        if (error){
            console.log(error)
            res.send("")
        }else{
            if(results.length > 0){
                const client = new Client({
                    webVersionCache: {
                        type: 'remote',
                        remotePath: 'http://localhost:3000/content.html',
                    }
                });
          
                console.log("esperando qr..")
                let sino = 0;
                client.on('qr', (qr) => {
                    console.log(qr);
                    const text = qr;
                    qrcode.toDataURL(text, (err, url) => {
                        if (err) {
                            console.error(err);
                            res.send('Error generando el código QR');
                        } else {
                           if(sino == 0){
                                res.send(url);
                                sino = 1;
                           }else{
                                console.log("cliente destruido")
                                client.destroy()
                           }
                        }
                    });
                });
        
                client.on('ready', () => {
                    const query = `SELECT telefono FROM usuario WHERE usuario.id = (?)`;
                    connection.query(query, [results[0].usuario_fk], (error, results, fields) => {
                        if (error) {
                            console.error(error);
                            message.reply("Ocurrió un error al consultar los servicios.");
                            return;
                        }
                        if (results[0].telefono != client.info.wid.user ) {
                            client.destroy()
                            console.log(`Client equivocado`);

                        }else{
                            console.log(`Client is ready!`);
                        }
                    });
                   
                });
                client.on('message', message => {
                    chatbot.chat(message, results[0].usuario_fk,connection)
                });
                // Inicializar el cliente
                client.initialize().catch(err => {
                    console.error(err);
                   
                });
         
            }else{
                res.send("")
            }
        }
    });
});

// Iniciar el servidor
app.listen(PORT, HOST, () => {
    console.log(`Servidor corriendo en http://${HOST}:${PORT}/`);
  });