

CREATE DATABASE IF NOT EXISTS abraceUmIdoso;
USE abraceUmIdoso;

-- 1. Tabela ENDERECO (Independente)
CREATE TABLE endereco (
    idEndereco INT AUTO_INCREMENT PRIMARY KEY,
    cep CHAR(8) NOT NULL,
    estado CHAR(2) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    numero CHAR(6),
    nmLogradouro VARCHAR(50) NOT NULL,
    tpLogradouro VARCHAR(15)
);

-- 2. Tabela CONTATOS (Independente)
CREATE TABLE contatos (
    idcontatos INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(45) NOT NULL,
    celular CHAR(11),
    telefone VARCHAR(11)
);

-- 3. Tabela PESSOA (Independente - Base para Voluntário e Idoso)
CREATE TABLE pessoa (
    idPessoa INT AUTO_INCREMENT PRIMARY KEY,
    nmPessoa VARCHAR(50) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    dtNascimento DATE NOT NULL,
    fotoPerfil VARCHAR(250),
    sobre VARCHAR(150)
);

-- 4. Tabela INSTITUICAO (Depende de Endereço e Contatos)
CREATE TABLE instituicao (
    idinstituicao INT AUTO_INCREMENT PRIMARY KEY,
    nmInstituicao VARCHAR(50) NOT NULL,
    cnpj VARCHAR(14) NOT NULL,
    senha VARCHAR(255) NOT NULL, -- Aumentado para suportar Hash seguro
    redesSociais VARCHAR(45),
    endereco_idEndereco INT NOT NULL,
    contatos_idcontatos INT NOT NULL,
    FOREIGN KEY (endereco_idEndereco) REFERENCES endereco(idEndereco),
    FOREIGN KEY (contatos_idcontatos) REFERENCES contatos(idcontatos)
);

-- 5. Tabela VOLUNTARIO (Depende de Pessoa, Endereço e Contatos)
CREATE TABLE voluntario (
    idVoluntario INT AUTO_INCREMENT PRIMARY KEY,
    senha VARCHAR(255) NOT NULL, -- Aumentado para suportar Hash seguro
    pessoa_idPessoa INT NOT NULL,
    endereco_idEndereco INT,
    contatos_idcontatos INT,
    FOREIGN KEY (pessoa_idPessoa) REFERENCES pessoa(idPessoa),
    FOREIGN KEY (endereco_idEndereco) REFERENCES endereco(idEndereco),
    FOREIGN KEY (contatos_idcontatos) REFERENCES contatos(idcontatos)
);

-- 6. Tabela IDOSO (Depende de Pessoa e Instituição)
CREATE TABLE idoso (
    idIdoso INT AUTO_INCREMENT PRIMARY KEY,
    pessoa_idPessoa INT NOT NULL,
    instituicao_idinstituicao INT NOT NULL,
    FOREIGN KEY (pessoa_idPessoa) REFERENCES pessoa(idPessoa),
    FOREIGN KEY (instituicao_idinstituicao) REFERENCES instituicao(idinstituicao)
);

-- 7. Tabela AGENDAMENTO (Depende de Voluntário e Idoso)
CREATE TABLE agendamento (
    idAgendamento INT AUTO_INCREMENT PRIMARY KEY,
    dtAgendamento DATE NOT NULL,
    hrAgendamento TIME NOT NULL,
    voluntario_idVoluntario INT NOT NULL,
    idoso_idIdoso INT NOT NULL,
    FOREIGN KEY (voluntario_idVoluntario) REFERENCES voluntario(idVoluntario),
    FOREIGN KEY (idoso_idIdoso) REFERENCES idoso(idIdoso)
);

-- 8. Tabela CARTAS (Depende de Voluntário e Idoso)
CREATE TABLE cartas (
    idCartas INT AUTO_INCREMENT PRIMARY KEY,
    textoCarta TEXT NOT NULL,
    data DATETIME NOT NULL,
    statusCarta CHAR(10) DEFAULT 'Pendente',
    voluntario_idVoluntario INT NOT NULL,
    idoso_idIdoso INT NOT NULL,
    FOREIGN KEY (voluntario_idVoluntario) REFERENCES voluntario(idVoluntario),
    FOREIGN KEY (idoso_idIdoso) REFERENCES idoso(idIdoso)
);

CREATE TABLE funcionario (
    idFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    cargo VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    
    -- Relacionamentos
    pessoa_idPessoa INT NOT NULL,
    contatos_idcontatos INT NOT NULL, -- Necessário para o login (email)
    instituicao_idinstituicao INT NOT NULL, -- O funcionário trabalha em algum lugar
    
    FOREIGN KEY (pessoa_idPessoa) REFERENCES pessoa(idPessoa),
    FOREIGN KEY (contatos_idcontatos) REFERENCES contatos(idcontatos),
    FOREIGN KEY (instituicao_idinstituicao) REFERENCES instituicao(idinstituicao)
);

SELECT * FROM endereco
SELECT * FROM pessoa
SELECT * FROM voluntario
SELECT * FROM contatos
SELECT * FROM funcionario


SELECT idVoluntario, idPessoa,nmPessoa, nmlogradouro, numero, bairro, email, telefone, celular,senha
FROM Voluntario AS V
left JOIN
pessoa AS P
ON
V.pessoa_idPessoa = P.idPessoa
LEFT JOIN
endereco AS E
on
V.idVoluntario = E.idEndereco
LEFT JOIN 
contatos AS c
ON
V.contatos_idcontatos = c.idcontatos




SELECT v.idVoluntario as id, v.senha, p.nmPessoa as nome 
                    FROM voluntario v
                    JOIN contatos c ON v.contatos_idcontatos = c.idcontatos
                    JOIN pessoa p ON v.pessoa_idPessoa = p.idPessoa
                    WHERE c.email = ?";
                    
SELECT f.idFuncionario as id, f.senha, p.nmPessoa as nome, f.instituicao_idinstituicao 
                    FROM funcionario f
                    JOIN contatos c ON f.contatos_idcontatos = c.idcontatos
                    JOIN pessoa p ON f.pessoa_idPessoa = p.idPessoa
                    WHERE c.email = ?";
        }
        
        