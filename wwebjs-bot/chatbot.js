let step = 0;
let turno = {
    servico: "",
    telefono: "",
    horario: "",
    dia: ""
}
function handleGreeting(message) {
    if (message.body.toLowerCase() === 'hola') {
        message.reply(`Hola, ${message.from}. ¿Qué necesita? (Escriba la letra según la opción que elija)\na - Pedir turno\nb - Ver mis turnos`);
        step = 1;
    } else {
        message.reply('Por favor, escribe "Hola" para comenzar.');
        step = 0;
        
    }
}

function handleOptionA(message, id_usuario, connection) {
    const query = `SELECT * FROM servicios WHERE servicios.usuario_fk = (?)`;
    connection.query(query, [id_usuario], (error, results, fields) => {
        if (error) {
            console.error(error);
            message.reply("Ocurrió un error al consultar los servicios.");
            return;
        }
        
        if (results.length === 0) {
            message.reply("Aún no hay ningún servicio.");
        } else {
            let resultadosTexto = "";
            results.forEach((result, i) => {
                resultadosTexto += `${i + 1}) Servicio: ${result.nombre} Precio: ${result.precio}\n `;
            });
            message.reply("Servicios disponibles:\n" + resultadosTexto);
            step= 2;
        }
    });
}

function handleOptionB(message, connection) {
    const query = `SELECT * FROM turnos WHERE turnos.telefono_cliente = (?)`;
    connection.query(query, [message.number], (error, results, fields) => {
        if (error) {
            console.error(error);
            message.reply("Ocurrió un error al consultar los turnos.");
            return;
        }
        
        if (results.length === 0) {
            message.reply("Aún no tiene ningún turno.");
        } else {
            let resultadosTexto = "";
            results.forEach(result => {
                resultadosTexto += `${result}\n`;
            });
            message.reply("Turnos pendientes:\n" + resultadosTexto);
        }
    });
}

function pedirservicio(message, id_usuario, connection) {
    const query = `SELECT * FROM servicios WHERE servicios.usuario_fk = (?)`;
    connection.query(query, [id_usuario], (error, results, fields) => {
        if (error) {
            console.error(error);
            message.reply("Ocurrió un error al consultar los servicios.");
            return;
        }
        
        if (results.length === 0) {
            message.reply("Aún no hay ningún servicio.");
        } else {
                let resultadosTexto = "";
            results.forEach((result, i) => {
                if(i+1 == parseInt(message.body)){
                    resultadosTexto = `Eligio el servicio: ${result.nombre} precio: ${result.precio}\n `;
                    turno.servico = result.id
                    turno.telefono = message.number
                }
            });
            message.reply(resultadosTexto);
            step= 3;
        }
    });
}

function chat(message, id_usuario, connection, client) {
    switch (step) {
        case 0:
            handleGreeting(message);
            break;
        case 1:
            if (message.body.toLowerCase() === 'a') {
                handleOptionA(message, id_usuario, connection);
            } else if (message.body.toLowerCase() === 'b') {
                handleOptionB(message, connection);
            } else {
                message.reply("Opción no válida. Por favor, seleccione 'a' o 'b'.");
                step = 0;
            }
            break;
        case 2:
            if (!isNaN(message.body)) {
                pedirservicio(message, id_usuario, connection);
            } else {
                message.reply("Opción no válida.");
                step = 1;
            }
            break;
        default:
            message.reply("Ocurrió un error inesperado.");
            step = 0;
            break;
    }
}

module.exports = {
    chat
};
