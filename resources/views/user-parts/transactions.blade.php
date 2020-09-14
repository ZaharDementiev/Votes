@extends('profile')

@section('inner')

    <div class="wrap-profile-content">
        <h2>Транзакции</h2>
        <div class="wrap-followers">
            <ul>
                @foreach($user->transactions as $transaction)
                    <li>
                        To id: {{$transaction->to_id}}
                        Amount: {{$transaction->amount}}
                        Transactions status: {{$transaction->transaction_status}}
                        <button type="button" onclick="openFeedbackForm({{$transaction->id}})"> Оставить отзыв </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="wrap-profile-content">
        <h2>Отзывы</h2>
        <div class="wrap-followers">
            <form id="form-feedback" method="POST">
                @csrf
                <input type="text" name="text">
                <input type="text" name="points">
                <input type="text" name="positive">
                <input type="text" id="example">
                <button type="submit" >Отправить</button>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        function openFeedbackForm(id)
        {
            let form = $('#form-feedback');
            let url = '/feedbacks/submit/' + id;
            form.attr('action', url);
        }
    </script>
@endsection