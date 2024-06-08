const http = require('http');

const server = http.createServer((req, res) => {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('Hello World\n');
});

server.listen(3000, '192.168.100.6', () => {
  console.log('Servidor Node.js en ejecución en http://192.168.100.6:3000/');
});
