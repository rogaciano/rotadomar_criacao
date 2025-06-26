const express = require('express');
const cors = require('cors');
const https = require('https');
const path = require('path');

const app = express();
const PORT = 3000;

// Habilitar CORS
app.use(cors());
app.use(express.json());

// Servir arquivos estáticos (HTML, CSS, JS)
app.use(express.static(path.join(__dirname)));

// Função para fazer requisição HTTPS
function makeHttpsRequest(url) {
    return new Promise((resolve, reject) => {
        https.get(url, (res) => {
            let data = '';
            
            res.on('data', (chunk) => {
                data += chunk;
            });
            
            res.on('end', () => {
                try {
                    const jsonData = JSON.parse(data);
                    resolve(jsonData);
                } catch (error) {
                    reject(new Error('Erro ao parsear JSON: ' + error.message));
                }
            });
        }).on('error', (error) => {
            reject(error);
        });
    });
}

// Rota proxy para a API
app.get('/api/estoque', async (req, res) => {
    try {
        console.log('📡 Fazendo requisição para a API...');
        
        const apiUrl = 'https://dapic.webpic.com.br/api/home/estoques?empresa=canalpernambuco&token=9EUVDSZKT8zh5uqirzgdPN3WKwWGGd&armazenador=Armazenador%20-%20Mat%C3%A9ria%20Prima%20-%20Tecido';
        
        const data = await makeHttpsRequest(apiUrl);
        
        console.log(`✅ Dados recebidos: ${data.length} itens`);
        res.json(data);
        
    } catch (error) {
        console.error('❌ Erro ao buscar dados:', error.message);
        res.status(500).json({ 
            error: 'Erro ao buscar dados da API',
            details: error.message 
        });
    }
});

// Rota principal
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(PORT, () => {
    console.log(`🚀 Servidor rodando em http://localhost:${PORT}`);
    console.log(`📁 Servindo arquivos de: ${__dirname}`);
    console.log(`🔗 API disponível em: http://localhost:${PORT}/api/estoque`);
});