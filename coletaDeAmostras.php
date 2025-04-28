<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coleta de Amostras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        header {
            background-color: #005f73;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            border-radius: 8px;
        }
        nav {
            margin: 20px 0;
            display: flex;
            gap: 20px;
        }
        nav a {
            text-decoration: none;
            color: #005f73;
            font-weight: bold;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        fieldset {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 15px;
        }
        legend {
            font-weight: bold;
            color: #005f73;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="datetime-local"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .checkbox-group {
            margin-top: 10px;
        }
        .checkbox-group label {
            font-weight: normal;
            display: inline-block;
            margin-right: 15px;
        }
        button {
            background-color: #005f73;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>Mapa de Bioquímica - Coleta de Amostras</header>

<nav>
    <a href="#">Mapa de Bioquímica</a>
    <a href="#">Coleta de Amostras</a>
    <a href="#">Registrar</a>
</nav>

<form>
    <fieldset>
        <legend>Dados do Paciente</legend>
        <label>Nome do Paciente:</label>
        <input type="text" name="nome_paciente">
        
        <label>Data e Hora da Coleta:</label>
        <input type="datetime-local" name="data_coleta">
    </fieldset>

    <fieldset>
        <legend>Exames do Paciente</legend>
        <label>Setor Hematologia:</label>
        <div class="checkbox-group">
            <label><input type="checkbox"> Hemograma</label>
            <label><input type="checkbox"> Leucograma</label>
            <label><input type="checkbox"> Plaquetas</label>
        </div>

        <label>Setor Parasitologia:</label>
        <div class="checkbox-group">
            <label><input type="checkbox"> EPF</label>
            <label><input type="checkbox"> Sangue Oculto</label>
        </div>

        <label>Setor Microbiologia:</label>
        <div class="checkbox-group">
            <label><input type="checkbox"> Urina</label>
            <label><input type="checkbox"> Escarro</label>
            <label><input type="checkbox"> Antibiograma</label>
        </div>

        <label>Setor Urinálise:</label>
        <div class="checkbox-group">
            <label><input type="checkbox"> Exame Macroscópico</label>
            <label><input type="checkbox"> Exame Químico</label>
            <label><input type="checkbox"> Exame Microscópico</label>
        </div>
    </fieldset>

    <fieldset>
        <legend>Coleta e Materiais</legend>
        <label>Tipo de Coleta Realizada:</label>
        <div class="checkbox-group">
            <label><input type="checkbox"> Sim</label>
        </div>

        <label>Tubos utilizados na coleta:</label>

        <label>Lote:</label>
        <input type="text" name="lote_tubo">

        <label>Validade:</label>
        <input type="text" name="validade_tubo">

        <div class="checkbox-group">
            <label><input type="checkbox"> Tubo com Tampa Vermelha</label>
            <label><input type="checkbox"> Tubo com Tampa Cinza</label>
            <label><input type="checkbox"> Tubo com Tampa Amarela</label>
            <label><input type="checkbox"> Tubo com Tampa Preta</label>
            <label><input type="checkbox"> Tubo com Tampa Azul Claro</label>
            <label><input type="checkbox"> Tubo com Tampa Roxa</label>
            <label><input type="checkbox"> Tubo com Tampa Verde</label>
        </div>

        <label>Seringa Utilizada:</label>
        <label>Tamanho:</label>
        <input type="text" name="tamanho_seringa">

        <label>Lote:</label>
        <input type="text" name="lote_seringa">

        <label>Validade:</label>
        <input type="text" name="validade_seringa">

        <label>Potes de Urina:</label>
        <label>Lote:</label>
        <input type="text" name="lote_urina">
        <label>Validade:</label>
        <input type="text" name="validade_urina">

        <label>Potes de Fezes:</label>
        <label>Lote:</label>
        <input type="text" name="lote_fezes">
        <label>Validade:</label>
        <input type="text" name="validade_fezes">
    </fieldset>

    <button type="submit">Registrar</button>
</form>

</body>
</html>
