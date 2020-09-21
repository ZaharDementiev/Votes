<article class="tape-content">
    <div class="tape-content-top">
        <div class="tape-content-btn">
            <a href="/posts/@{{post.slug}}">
            <img ng-if="post.type == 'post'" src="/img/tape/star.svg" alt="">
            <img ng-if="post.type == 'vote'" src="/img/tape/speaker.svg" alt="">
            <img ng-if="post.type == 'question'" src="/img/tape/ask.svg" alt="">
            </a>
        </div>
        <div class="tape-content-time">
            <span ng-if="post.favorited_at">
                @{{$moment(post.favorited_at).tz('Europe/Moscow').fromNow()}}/
                @{{$moment(post.created_at).tz('Europe/Moscow').fromNow()}}
            </span>
            <span ng-if="!post.favorited_at">@{{$moment(post.created_at).tz('Europe/Moscow').fromNow()}}</span>
        </div>
    </div>
    
    <div class="tape-content-body">
        <a href="/posts/@{{post.slug}}">
            <p style="font-weight: bold">@{{post.title}}</p>
            <p ng-cloak ng-bind-html="limited(post)"></p>
            <div class="img_post" ng-if="post.images.length">
                <img src="/storage/images/posts/@{{post.images[0].path}}" alt="">
            </div>
        </a>
    </div>
    <div class="tape-content-foot">
        <div class="user-tape" ng-if="post.is_anonimous">
            <div class="user-tape-img" style="background-image: url(/storage/images/avatars/default.jpg)">
            </div>
            <span><a>Аноним</a></span>
        </div>
        <div class="user-tape" ng-if="!post.is_anonimous">
            <div class="user-tape-img" style="background-image: url(/storage/images/avatars/@{{post.avatar}})">
            </div>
            <span><a href="/user/@{{post.user_name}}">@{{post.user_name}} (@{{post.age}})</a></span>
        </div>
        
        <div class="tape-activity tape-activity-feed">
            <div class="tape-activity-el">
                <div ng-if="post.hasLiked" class="tape-activity__icon like active"></div>
                <div ng-if="!post.hasLiked" class="tape-activity__icon like"></div>

                <span class="tape-activity-el-count">@{{shorten(post.likers_count)}}</span>
            </div>
            <div class="tape-activity-el">
                <div ng-if="post.hasDisliked" class="tape-activity__icon dislike active"></div>
                <div ng-if="!post.hasDisliked" class="tape-activity__icon dislike"></div>
                <span class="tape-activity-el-count">@{{shorten(post.bookmarkers_count)}}</span>
            </div>
            <div class="tape-activity-el">
                <div ng-if="post.hasFavorited" class="tape-activity__icon bookmark active"></div>
                <div ng-if="!post.hasFavorited" class="tape-activity__icon bookmark"></div>
                <span class="tape-activity-el-count">@{{shorten(post.favoriters_count)}}</span>
            </div>
            <div class="tape-activity-el">
                <div class="tape-activity__icon comments"></div>
                <span class="tape-activity-el-count">@{{shorten(post.comments_count)}}</span>
            </div>
        </div>
    </div>
</article>
