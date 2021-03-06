{include file='header.tpl'}
<main class="container">
<div class="listaUsuarios">
    <h1>Lista de Usuarios</h1>
</div>
<ul class='list-group mt-5'>
    {foreach $users as $user}
        <li class='listaUsuarioView'>
              {$user->nombre_administrador} | {$user->email} | {$user->permiso}
            <div class='cajaBtn'>
            {if $user->permiso == 0}
                <a class='btnEditarUsuario' href='permisoUsuario/{$user->id}'>Agregar Permiso</a>
                {else}
                <a class='btnEditarUsuario' href='permisoUsuario/{$user->id}'>Quitar Permiso</a>
            {/if}
                <a class='btnBorrarUsuario' href='eliminarUsuario/{$user->id}'>Eliminar</a>
            </div>
        </li>
    {/foreach}
</ul>
{include file='footer.tpl'}