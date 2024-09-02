<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">restablece tu password escribiendo tu email a continuación</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu email"
            name="email"
        />
    </div>

    <input type="submit" class="boton" value="Enviar">
</form>

<div class="acciones">
    <a href="/">¿ya tienes una cuenta? Inicia sesión</a>
   <a href="/crear-cuenta">¿Aún no tienes cuenta? crea una</a>
</div>