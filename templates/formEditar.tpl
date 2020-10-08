{include file="head.tpl"}
{include file='header.tpl'}
<main class="container"> 
<div class="btnLogout"><a href="logout">Logout</a></div> 
<div class="container">
    <h1 class="listaProductos"> Edita tus Productos</h1>
    <form action="editarProducto/{$id}" method="POST">
        <div class="form-group">
            <label for="title">Nombre</label>
            <input class="form-control" id="title" name="input_title" aria-describedby="emailHelp" required>
        </div>
        <div class="form-group">
            <label for="description">Descripcion</label>
            <input class="form-control" name="input_description" id="description" required>
        </div>
        <div class="form-group">
            <label for="priority">Precio</label>
            <input class="form-control" name="input_precio" id="precio" required>
        </div>
        <div class="form-group">
            <label for="title">Categoria</label>
            {include file="listaCategorias.tpl"}
        </div>
        <button type="submit" class="btn btn-primary">Editar</button>
    </form>
</div> 
{include file='footer.tpl'}