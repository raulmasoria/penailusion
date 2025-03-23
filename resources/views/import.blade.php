<h1>IMPORTACIÓN DESDE XLSX O CSV</h1>
<h2>Importar usuarios</h2>
<form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Importar Usuarios</button>
</form>
<br>
<h2>Importar Direcciones</h2>
<form action="{{ route('adress.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Importar Direcciones</button>
</form>
<br>
<h2>Importar permanencias y antigüedad</h2>
<form action="{{ route('antiquity.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Importar antigüedad</button>
</form>
<br>
<h2>Importar padrinos</h2>
<form action="{{ route('godfather.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Importar padrinos</button>
</form>
<br>
<h2>Importar niños</h2>
<form action="{{ route('children.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Importar niños</button>
</form>
<br>