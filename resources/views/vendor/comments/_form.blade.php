<div class="card">
    <div class="card-body">
        @if($errors->has('commentable_type'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->get('commentable_type') }}
            </div>
        @endif
        @if($errors->has('commentable_id'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->get('commentable_id') }}
            </div>
        @endif
        <form id="fcomment" method="POST" action="{{ url('comments') }}">
            @csrf
            <input type="hidden" name="commentable_type" value="\{{ get_class($model) }}" />
            <input type="hidden" name="commentable_id" value="{{ $model->id }}" />

            <div class="detail-content-comments-add">
                <div class="textarea-block">
                    <textarea name="message" class="textarea-block__textarea" data-emojiable="true" placeholder="Напишите сообщение"></textarea>
                    <div class="textarea-block-media">
                        <div class="textarea-block-media_el">
                            <img-upload></img-upload>
                        </div>
                    </div>
                </div>
                <div class="btn-green">
                    <a onclick="sendComment()" >Отправить</a>
                </div>
            </div>
        </form>
    </div>
</div>
<br />
