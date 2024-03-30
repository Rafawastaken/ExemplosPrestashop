<?php

class CommentClass extends ObjectModelCore
{
  public $id;
  public $user_id;
  public $comment;

  public static $definition = [
    'table' =>  "testcomment",
    'primary' => "id",
    'multilang' => false,
    'multilang_shop' => false,

    "fields" => [
      "user_id" => [
        'type' => self::TYPE_INT,
        'size' => 11,
        'validate' => 'isunsignedInt',
        'required' => true
      ],
      "comment" => [
        'type' => self::TYPE_STRING,
        'size' => 255,
        'validate' => 'isCleanHtml',
        'required' => true
      ]
    ]
  ];
}
