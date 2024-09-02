<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if($error) return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input
         type="password"
         id="password"
         name="password"
         placeholder="Tu nuevo password"
         />
    </div>
    <input type="submit" class="boton" value="Guardar Nuevo Password">
</form>

<div class="acciones">
    <a href="/">¿ya tienes cuenta? inicia sesión</a>
    <a href="/crear-cuenta">¿Aun no tienes cuenta? crear una</a>
</div>