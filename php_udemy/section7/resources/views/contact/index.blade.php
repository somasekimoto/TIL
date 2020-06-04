@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{ route('contact.create') }}">新規登録</a>
                    <br>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="{{ route('contact.index') }}">
                      <input class="form-control mr-sm-2" type="search" name="search" placeholder="検索" aria-label="Search">
                      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索する</button>
                    </form>
                    <table class="table table-dark">
                      <thead>
                          <tr>
                            <th scope="col">id</th>
                            <th scope="col">氏名</th>
                            <th scope="col">件名</th>
                            <th scope="col">登録日時</th>
                            <th scope="col">詳細</th>
                          </tr>
                      </thead>
                      <tbody>
                      @foreach($contacts as $contact)
                        <tr>
                          <th>{{ $contact->id }}</th>
                          <td>{{ $contact->your_name }}</td>
                          <td>{{ $contact->title }}</td>
                          <td>{{ $contact->created_at }}</td>
                          <td><a href="{{ route('contact.show', ['id' => $contact->id ]) }}">詳細を見る</a></td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
