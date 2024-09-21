<h1 class="nombre-pagina">LOGIN</h1>
<p class="descripcion-pagina">Inicia Sessión con tus datos</p>
<?php 
    include_once __DIR__ . "/../templates/alertas.php"
    
?>
<!-- -->
<form class="formulario" method="post" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input 
        type="email" 
        id="email" 
        name="email" 
        placeholder="Tu Email"
        
        >
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
        type="password" 
        id="password" 
        name="password" 
        placeholder="Tu password">
    </div>
    <input type="submit"  class="boton" value="Iniciar Seccion">
</form>
<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta?, Crear una cuenta</a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>