const express=require('express');
const path = require('path');

const app = express();

app.use(express.static('./dist/nov-movies'));

app.get('/*', (req, res) =>
        res.sendFile('index.html', { root: './dist/nov-movies/' }),
        );

app.listen(process.env.PORT || 8080);
