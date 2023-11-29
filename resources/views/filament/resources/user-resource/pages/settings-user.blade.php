<x-filament-panels::page>
    <h1>Configurações de usuários</h1>
    @php
        $users = \App\Models\User::all();





    @endphp

    @foreach ($users as $user)
        <p>{{$user->name}}</p>
        @endforeach

</x-filament-panels::page>
