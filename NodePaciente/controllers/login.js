const express = require('express');
const router = express.Router();
const jwt = require('jsonwebtoken');


//Simulando um banco de dados
const USERS = [
    {id: 1, login: 'alex', password: '123465', name: 'Alex Nunes'},
    {id: 2, login: 'helton', password: '654321', name: 'Helton Azevedo'},
    {id: 3, login: 'juliana', password: 'xpto', name: 'Juliana Silva'},
    {id: 4, login: 'joao', password: 'abacaxi', name: 'João Victor'}
];

//Chave secreta do Token
const JWT_SECRET = 'Pêra';

//Essa função irá gerar o token
function signToken(user){
    const tokenGerado = jwt.sign({sub: user.id, userName: user.name}, JWT_SECRET, {expiresIn: '1h'}); //primeiro parametro: 'payload', segundo: 'jwt', terceira: qnt tempo expira
    console.log(`Token gerado: ${tokenGerado}`);
    return tokenGerado;
}

router.post('/login', (req, res) => {
    const {plogin, psenha} = req.body || {};
    console.log("login: ", plogin, "senha: ", psenha)
    if(!plogin || !psenha) return res.status(400).json({error: true, message: 'Usuário e/ou senha não informados'});

    const user = USERS.find(u => u.login === plogin && u.password === psenha);
    if(!user) return res.status(401).json({error: true, message: 'Usuário e/ou senha inválidos'});

    const token = signToken(user);
    res.status(200).json({token});
});

//Criando um middleware para interceptar as rotas
function auth(req, res, next){
    const header = req.headers.authorization || "";
    const [type, token] = header.split(" ");
    if(type !== 'Bearer' || !token) return res.status(401).json({error: true, message: 'Token não informado'});
    try{
        req.user = jwt.verify(token, JWT_SECRET);
        next();
    }catch(error){
        console.error("Erro ao validar token: ", error);
        res.status(401).json({error: true, message: 'Erro ao validar Token'});
    }
};

module.exports = { router, auth };