let userStates = {};

function initializeUserState(userId) {
    userStates[userId] = {
        step: 0,
        turno: { servicio: "", telefono: "", horario: "", dia: "", diaeeuu: "", nombre: "" },
        dias_no_laborales: null,
        horarios: null,
        volver: false,
        diaSeleccionado: null
    };
}

function getUserState(userId) {
    if (!userStates[userId]) {
        initializeUserState(userId);
    }
    return userStates[userId];
}

function getDiasDisponibles(state) {
    let hoy = new Date();
    hoy.setDate(hoy.getDate() + 1); // Empieza a partir de mañana
    let diasDisponibles = [];

    while (hoy.getMonth() === new Date().getMonth()) {
        let diaSemana = hoy.toLocaleDateString('es-ES', { weekday: 'long' }).toLowerCase();
        if (!state.dias_no_laborales.includes(diaSemana) && state.horarios[diaSemana]) {
            diasDisponibles.push(hoy.toLocaleDateString('es-ES'));
        }
        hoy.setDate(hoy.getDate() + 1);
    }

    return diasDisponibles;
}

function getHorariosDisponibles(diaSeleccionado, state) {
    let fecha = new Date(diaSeleccionado.split("/").reverse().join("-"));
    let diaSemana = fecha.toLocaleDateString('es-ES', { weekday: 'long' }).toLowerCase();
    let horariosDia = state.horarios[diaSemana];
    let horariosDisponibles = [];

    horariosDia.forEach(hora => {
        horariosDisponibles.push(hora)
    });

    return horariosDisponibles;
}

function handleGreeting(message, state) {
    if (message.body.toLowerCase() === 'hola' || state.volver == true) {
        state.volver = false;
        message.reply(`Hola, ${message.from}. ¿Qué necesita? (Escriba la letra según la opción que elija)\na - Pedir turno\nb - Ver mis turnos`);
        state.step = 1;
    } else {
        message.reply('Por favor, escribe "Hola" para comenzar.');
        state.step = 0;
    }
}

function handleOptionA(message, id_usuario, connection, state) {
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
                resultadosTexto += `${i + 1}) Servicio: ${result.nombre} Precio: ${result.precio}$\n `;
            });
            message.reply("Servicios disponibles:\n0) Para volver al menú\n" + resultadosTexto);
            state.step = 2;
        }
    });
}

function handleOptionB(message, connection, state) {
    const query = `SELECT * FROM turnos WHERE turnos.telefono_cliente = (?)`;
    connection.query(query, [message.from], (error, results, fields) => {
        if (error) {
            console.error(error);
            message.reply("Ocurrió un error al consultar los turnos.");
            return;
        }

        if (results.length === 0) {
            message.reply("Aún no tiene ningún turno.");
        } else {
            let resultadosTexto = "";
            let cont = 1;
            results.forEach(result => {
                
                const query2 = `SELECT * FROM servicios WHERE servicios.id = (?)`;
                connection.query(query2, [results.servicio_fk], (error, results2, fields) => {
                    if (error) {
                        console.error(error);
                        message.reply("Ocurrió un error al consultar los turnos.");
                        return;
                    }

                    if (results2.length === 0) {
                        message.reply("Aún no tiene ningún turno.");
                    } else {
                        results2.forEach(result2 => {
                            resultadosTexto += cont+`) ${result2.nombre}\nPrecio: ${result2.precio}$`;
                        });
                        message.reply("Turnos pendientes:\n" + resultadosTexto);
                    }
                });
            });
            message.reply("Turnos pendientes:\n" + resultadosTexto);
        }
    });
}

function pedirServicio(message, id_usuario, connection, state) {
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
            if (!state.volver) {
                let resultadosTexto = "";
                results.forEach((result, i) => {
                    if (i + 1 == parseInt(message.body)) {
                        resultadosTexto = `Eligió el servicio: ${result.nombre} precio: ${result.precio}\n `;
                        state.turno.servicio = result.id;
                        state.turno.telefono = message.from;
                    }
                });
                message.reply(resultadosTexto);
            }

            state.volver = false;
            const query2 = `SELECT * FROM horarios WHERE horarios.usuario_fk = (?)`;
            connection.query(query2, [id_usuario], (error, results, fields) => {
                if (error) {
                    console.error(error);
                    message.reply("Ocurrió un error al consultar los horarios.");
                    return;
                }

                if (results.length === 0) {
                    message.reply("Aún no hay ningún horario.");
                } else {
                    state.horarios = JSON.parse(results[0].horarios);
                    state.dias_no_laborales = JSON.parse(results[0].dias_no_laborales);
                   
                    let diasDisponibles = getDiasDisponibles(state);
                    let diasTexto = diasDisponibles.map((dia, i) => `${i + 1}) ${dia}`).join("\n");
                    message.reply("Seleccione el día del turno:\n0) Para elegir otro servicio\n" + diasTexto);
                    state.step = 3;
                }
                
            });
        }
    });
}

function elegirDia(message, state) {
    let diasDisponibles = getDiasDisponibles(state);
    if (!state.volver) {
        state.diaSeleccionado = diasDisponibles[parseInt(message.body) - 1];
    }
    state.volver = false;
    if (state.diaSeleccionado) {
        const partesFecha = state.diaSeleccionado.split("/");   
        const fechaEEUU = `${partesFecha[2]}-${partesFecha[1]}-${partesFecha[0]}`;
        state.turno.diaeeuu = fechaEEUU;
        state.turno.dia = state.diaSeleccionado;

        let horariosDisponibles = getHorariosDisponibles(state.diaSeleccionado, state);
        let horariosTexto = horariosDisponibles.map((hora, i) => `${i + 1}) ${hora}`).join("\n");
        message.reply("Eligió: " + state.turno.dia + "\nSeleccione el horario del turno:\n0) Para elegir otra fecha\n" + horariosTexto);
        state.step = 4;
    } else {
        message.reply("Día no válido. Por favor, seleccione un día válido.");
        state.step = 3;
    }
}

function elegirHorario(message, state) {
    let horariosDisponibles = getHorariosDisponibles(state.turno.dia, state);
    let horarioSeleccionado = horariosDisponibles[parseInt(message.body) - 1];

    if (horarioSeleccionado) {
        state.turno.horario = horarioSeleccionado;
        message.reply("Eligió: " + state.turno.horario + "\nPor favor, ingrese su nombre para confirmar el turno:\n0) Para elegir otro horario\n");
        state.step = 5;
    } else {
        message.reply("Horario no válido. Por favor, seleccione un horario válido.");
        state.step = 4;
    }
}

function confirmarTurno(message, id_usuario, connection, state) {
    state.turno.nombre = message.body;

    const query = `INSERT INTO turnos (servicio_fk, vendedor_fk, fecha, horario, estado, nombre_cliente, telefono_cliente, apellido_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)`;
    const values = [state.turno.servicio, id_usuario, state.turno.diaeeuu, state.turno.horario, 'agendado', state.turno.nombre, state
        .turno.telefono, ''];

        connection.query(query, values, (error, results, fields) => {
            if (error) {
                console.error(error);
                message.reply("Ocurrió un error al confirmar el turno.");
                return;
            }
            message.reply("Turno confirmado con éxito.");
            state.step = 0;
            state.volver = false;
            state.turno = { servicio: "", telefono: "", horario: "", dia: "", diaeeuu: "", nombre: "" };
        });
    }
    
    function chat(message, id_usuario, connection, client) {
        let state = getUserState(message.from);
    
        switch (state.step) {
            case 0:
                handleGreeting(message, state);
                break;
            case 1:
                if (message.body.toLowerCase() === 'a') {
                    handleOptionA(message, id_usuario, connection, state);
                } else if (message.body.toLowerCase() === 'b') {
                    handleOptionB(message, connection, state);
                } else {
                    message.reply("Opción no válida. Por favor, seleccione 'a' o 'b'.");
                    state.step = 0;
                }
                break;
            case 2:
                if (!isNaN(message.body)) {
                    if (message.body != 0) {
                        pedirServicio(message, id_usuario, connection, state);
                    } else {
                        state.step = 0;
                        state.volver = true;
                        handleGreeting(message, state);
                    }
                } else {
                    message.reply("Opción no válida.");
                    state.step = 1;
                }
                break;
            case 3:
                if (message.body == 0) {
                    state.step = 1;
                    handleOptionA(message, id_usuario, connection, state);
                } else {
                    elegirDia(message, state);
                }
                break;
            case 4:
                if (message.body == 0) {
                    state.volver = true;
                    state.step = 2;
                    pedirServicio(message, id_usuario, connection, state);
                } else {
                    elegirHorario(message, state);
                }
                break;
            case 5:
                if (message.body == 0) {
                    state.volver = true;
                    state.step = 3;
                    elegirDia(message, state);
                } else {
                    confirmarTurno(message, id_usuario, connection, state);
                }
                break;
            default:
                message.reply("Ocurrió un error inesperado.");
                state.step = 0;
                break;
        }
    }
    
    module.exports = {
        chat
    };
    