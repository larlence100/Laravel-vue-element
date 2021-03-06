<?php

namespace App\Transformers;

use App\Comment;
use App\Scopes\StatusScope;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['user'];

    protected $availableIncludes = [ 'user' ];

    public function transform(Comment $comment)
    {
        $content = json_decode($comment->content);

        return [
            'id'            => $comment->id,
            'user_id'       => $comment->user_id,
            'username'      => isset($comment->user) ? $comment->user->name : 'Null',
            'avatar'        => isset($comment->user) ? $comment->user->avatar : config('blog.default_avatar'),
            'commentable'   => isset($comment->commentable) ? $comment->commentable->title : 'Be Forbidden!',
            'commentable_type'          => $comment->commentable_type,
            'content_raw'   => $content->raw,
            'created_at'    => $comment->created_at->diffForHumans(),
            'like'          => false,
            'like_num'      => 0,
        ];
    }

    /**
     * Include User
     *
     * @param Comment $comment
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUser(Comment $comment)
    {
        $user = $comment->user;

        return $this->item($user, new UserTransformer);
    }

}
