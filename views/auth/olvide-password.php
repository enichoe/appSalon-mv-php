<h1 class="nombre-pagina">OLVIDE MI PASSWORD</h1>
<p class="descripcion-pagina">Establece tu password escribe tu email a continuación</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php"
?>

<form class="formulario" method="post" action="/olvide">
    <div class="campo">
        <label for="email">Email</label>
        <input 
        type="text" 
        id="email" 
        name="email" 
        placeholder="Tu email">
    </div>
    <input 
    type="submit" 
    value="Enviar Instrucciones" 
    class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta ? Inicia Sessión</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
</div>