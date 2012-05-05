<?php

return array(
    'engine' => array(
        'db.table.user' => array(
            'user_profile_avatar',
            'user_profile_foto'
        ),
        'db.table.blog' => array(
            'blog_description',
            'blog_avatar'
        ),
        'db.table.topic_content' => array(
            'topic_text',
            'topic_text_short',
            'topic_text_source'
        ),
        'db.table.comment' => array(
            'comment_text'
        ),
        'db.table.talk' => array(
            'talk_text'
        ),
        'db.table.topic_photo' => array(
            'path'
        ),
    ),
    'plugins' => array(
        'page' => array(
            'db.table.page' => array(
                'page_text'
            )
        )
    )
);
