{include file='header.tpl'}
    <div class="tituloTablaProductos">
        <h1>Nuestra Lista de Productos</h1>
    </div>
    
    <table class="tablaProductos">
        <thead class="thead">
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Categoria</th>
                <th>Descripcion</th>
            </tr>
        </thead>
        <tbody class="tbody">
        {foreach $productos as $producto}
            <tr>
                <td>{$producto->nombre}</td>
                <td>{$producto->precio}</td>
                <td>{$producto->nombre_categoria}</td>
                <td><a class='btnDetalle' href='detalleProducto/{$producto->id}'>
                    <i class="iconoDescripcion far fa-list-alt">
                </i></a></td>
            </tr>    
        {/foreach} 
        </tbody>
    </table>
{include file='footer.tpl'}