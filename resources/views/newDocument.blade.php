<form action="{{route('send-file')}}" method="POST">
    @csrf
    <input type="file" name="file">
    <button type="submit">Enviar</button>
</form>
