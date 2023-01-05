<form action="{{route('send-file')}}" method="POST">
    @csrf
    <input type="text" name="caminho" placeholder="caminho" required/>
    <input type="file" name="arquivo" placeholder="arquivo" />
    {{-- <input type="text" name="tipo_pessoa" placeholder="tipo pessoa PF / PJ"/> --}}
    <button type="submit">Enviar</button>
</form>


09752732992
