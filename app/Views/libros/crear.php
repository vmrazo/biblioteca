<?=$header?>
Formulario de crear

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Ingresar datos de libro</h5>
        <p class="card-text">
            <form method="post" action="<?=site_url('/guardar') ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" value="<?=old('nombre')?>" class="form-control" name="nombre" placeholder="Nombre" id="nombre">
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen</label>
                    <input type="file" class="form-control" name="imagen" placeholder="Imagen" id="imagen">
                </div>
                <button class="btn btn-success" type="submit">Guardar</button>
                <a href="<?=base_url('listar') ?>" class="btn btn-info">Cancelar</a>
            </form>
        </p>
    </div>
</div>
<?=$footer?>
