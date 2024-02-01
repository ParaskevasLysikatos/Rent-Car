const express=require('express');
const path = require('path');

const app = express();

app.use(express.static('./dist/rentalbook-frontend'));

app.get('/*', (req, res) =>
        res.sendFile('index.html', { root: './dist/rentalbook-frontend/' }),
        );

app.listen(process.env.PORT || 8080);
