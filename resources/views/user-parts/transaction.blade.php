<div class="wrap-profile-content">
    <h2>Отзывы</h2>
    <div class="wrap-followers">
        <form action={{route('transaction-submit')}} method="POST">
            @csrf
            <input type="text" name="amount">
            <input type="text" name="to_id">
            <button type="submit">Отправить</button>
        </form>
    </div>
</div>


