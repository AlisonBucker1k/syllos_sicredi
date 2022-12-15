<form action="{{route('send-file')}}" method="POST">
    @csrf
    <input type="text" name="nome_pessoa" placeholder="nome">
    <input type="text" name="cpfcnpj" placeholder="n documento"/>
    {{-- <input type="text" name="tipo_pessoa" placeholder="tipo pessoa PF / PJ"/> --}}
    <input type="file" name="file">
    <button type="submit">Enviar</button>
</form>


09752732992
