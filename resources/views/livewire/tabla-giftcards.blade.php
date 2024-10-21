<div>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Emisor</th>
                <th>Receptor</th>
                <th>Valor</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($giftcards as $giftcard)
                <tr>
                    <td>{{ $giftcard->id }}</td>
                    <td>{{ $giftcard->emisor }}</td>
                    <td>{{ $giftcard->receptor }}</td>
                    <td>{{ $giftcard->valor }}</td>
                    <td>{{ $giftcard->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
