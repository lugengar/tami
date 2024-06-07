let mysql = require("mysql");
function conexion(){
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
}
function consulta(query,variables){
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
        }
    });
    connection.query(query,variables, (error, results, fields) => {
        if (error) {
            console.log(error)
            return error;
        }else{
            return results;
        }
    });
    connection.end()
}
module.exports = {
    consulta,
    conexion 
};
