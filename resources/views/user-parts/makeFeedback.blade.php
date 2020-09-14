<div class="wrap-profile-content">
    <h2>Отзывы</h2>
    <div class="wrap-followers">
        <form action={{route('feedback-submit', $transaction)}} method="POST">
            @csrf
            <input type="text" name="text">
            <input type="text" name="points">
            <input type="text" name="positive">
            <button type="submit">Подтвердить</button>
        </form>
    </div>
</div>


