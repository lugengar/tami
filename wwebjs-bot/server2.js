require('dotenv').config();
const express = require('express');
const qrcode = require('qrcode');
const mysql = require('mysql');
const { Client } = require('whatsapp-web.js');
const basededatos = require('./basededatos.js');
const chatbot = require('./chatbot.js');
const app = express();
const port = process.env.SERV_PORT;

const dbConfig = {
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME
};

const pool = mysql.createPool(dbConfig);

app.use(express.static('public'));

app.get('/', (req, res) => {
    res.sendFile(__dirname + '/index.html');
});

app.get('/generate-qr/', (req, res) => {
    const { text1, text2 } = req.query;

    if (!text1 || !text2) {
        return res.status(400).send('Faltan parámetros requeridos');
    }

    pool.getConnection((err, connection) => {
        if (err) {
            console.error('Error de conexión a la base de datos:', err);
            return res.status(500).send('Error de conexión a la base de datos');
        }

        const query = "SELECT * FROM chatbot WHERE nombre = ? AND contraseña = ?";
        connection.query(query, [text1, text2], (error, results) => {
            if (error) {
                console.error('Error en la consulta:', error);
                connection.release();
                return res.status(500).send('Error en la consulta');
            }

            if (results.length === 0) {
                connection.release();
                return res.status(404).send('No se encontraron resultados');
            }else{
                const client = new Client({
                    webVersionCache: {
                        type: 'remote',
                        remotePath: process.env.RECURSO,
                    }
                });
                console.log("esperando qr")
                let sino = 0;

                client.on('qr', qr => {
                    qrcode.toDataURL(qr, (err, url) => {
                        if (err) {
                            console.error('Error generando el código QR:', err);
                            connection.release();
                            client.destroy();
                            return res.status(500).send('Error generando el código QR');
                        }
                        if(sino == 0){
                            res.send(url);
                            sino = 1;
                    }else{
                            client.destroy()
                    }
                    
                    });
                });

                client.on('ready', () => {
                    const userQuery = "SELECT telefono FROM usuario WHERE id = ?";
                    connection.query(userQuery, [results[0].usuario_fk], (userError, userResults) => {
                        if (userError || userResults[0].telefono !== client.info.wid.user) {
                            console.error(userError || 'Cliente equivocado');
                            client.destroy();
                            connection.release();
                            return;
                        }
                        console.log('Client is ready!');
                    });
                });

                client.on('message', message => {
                    chatbot.chat(message, results[0].usuario_fk, connection);
                });

                client.initialize().catch(err => {
                    console.error('Error inicializando el cliente:', err);
                    connection.release();
                });
            }
        });
    });
});

app.listen(process.env.SERV_PORT, process.env.SERV_HOST, () => {
    console.log(`Servidor escuchando en http://${process.env.SERV_HOST}:${process.env.SERV_PORT}`);
});