<h1 class="nombre-pagina">RECUPERAR PASSWORD</h1>
<p class="descripcion-pagina">Coloca tu nuevo Password a continucación</p>
<?php 
    include_once __DIR__ . "/../templates/alertas.php"
?>
<?php if($error) return; ?>

<form class="formulario"method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input 
        type="password" 
        id="password" 
        name="password" 
        placeholder="Tu nuevo Password">
    </div>
    <input type="submit" class="boton" value="Guarda tu nuevo Password">
</form>
<div class="acciones">
    <a href="/">¿Ya tienes cuenta?, Iniciar Sessión</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta , Crear una?</a>
</div>