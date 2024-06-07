const { Client, LocalAuth } = require('whatsapp-web.js');
const mysql = require('mysql2/promise');
const qrcode = require('qrcode-terminal');
const fs = require('fs');
const path = require('path');

// Configuración de la conexión a MySQL
const dbConfig = {
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'tami'
};

/*async function saveSessionToDatabase(sessionId, session) {
    try {
        const sessionString = JSON.stringify(session);
        if (!sessionString) {
            throw new Error('Session string is undefined or null');
        }

        const connection = await mysql.createConnection(dbConfig);
        await connection.execute('REPLACE INTO auth (session_id, session) VALUES (?, ?)', [sessionId, sessionString]);
        await connection.end();
        console.log('Sesión guardada en la base de datos');
    } catch (error) {
        console.error('Error al guardar la sesión en la base de datos:', error);
    }
}*/
async function saveSessionToDatabase(sessions) {
    try {
        const connection = await mysql.createConnection(dbConfig);
        await connection.execute('DELETE FROM auth'); // Limpiar cualquier sesión previa

        const insertPromises = sessions.map(async (session) => {
            const sessionString = JSON.stringify(session);
            if (!sessionString) {
                throw new Error('Session string is undefined or null');
            }
            await connection.execute('INSERT INTO auth (session) VALUES (?)', [sessionString]);
        });

        await Promise.all(insertPromises);

        await connection.end();
        console.log('Sesiones guardadas en la base de datos');
    } catch (error) {
        console.error('Error al guardar las sesiones en la base de datos:', error);
    }
}


async function getSessionFromDatabase(sessionId) {
    try {
        const connection = await mysql.createConnection(dbConfig);
        const [rows] = await connection.execute('SELECT session FROM auth WHERE session_id = ? LIMIT 1', [sessionId]);
        await connection.end();
        return rows.length ? JSON.parse(rows[0].session) : null;
    } catch (error) {
        console.error('Error al obtener la sesión de la base de datos:', error);
        return null;
    }
}

async function initializeClient(sessionId) {
    console.log(`Inicializando cliente para la sesión: ${sessionId}`);

    const client = new Client({
        authStrategy: new LocalAuth({
            clientId: sessionId
        }),
        webVersionCache: {
            type: 'remote',
            remotePath: 'https://raw.githubusercontent.com/wppconnect-team/wa-version/main/html/2.2412.54.html',
        }
    });

    client.on('qr', (qr) => {
        qrcode.generate(qr, { small: true });
    });

    client.on('authenticated', async (session) => {
        if (session) {
            console.log(`Sesión ${sessionId} autenticada:`, session);
            await saveSessionToDatabase(sessionId, session);
        } else {
            console.log(`Sesión ${sessionId} autenticada pero sin datos de sesión.`);
        }
    });

    client.on('auth_failure', () => {
        console.log(`Falló la autenticación para la sesión ${sessionId}, por favor vuelve a autenticar.`);
    });

    client.on('ready', () => {
        console.log(`Cliente para la sesión ${sessionId} está listo`);
    });
    client.on('message', message => {
		message.reply('Por favor, escribe "Hola" para comenzar.');
    });
    await client.initialize();
    return client;
}

async function createClient(sessionId) {
    console.log(`Creando cliente para la sesión: ${sessionId}`);

    const session = await getSessionFromDatabase(sessionId);
    if (session) {
        const sessionDir = path.join('./.wwebjs_auth', sessionId);
        if (!fs.existsSync(sessionDir)) {
            fs.mkdirSync(sessionDir, { recursive: true });
        }
        fs.writeFileSync(path.join(sessionDir, 'session'), JSON.stringify(session));
        console.log(`Sesión ${sessionId} restaurada desde la base de datos`);
    }

    return initializeClient(sessionId);
}

(async () => {
    // Lista de IDs de sesiones. Cada ID debe ser único.
    const sessionIds = ['session1','session2'];

    for (const sessionId of sessionIds) {
        await createClient(sessionId);
    }
})();
