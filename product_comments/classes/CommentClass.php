<?php

class CommentClass extends ObjectModelCore
{
  public $id;
  public $user_id;
  public $comment;
  public $visible;

  public static $definition = [
    'table' => "productcomments",
    'primary' => "id",
    'multilang' => false,
    'multilang_shop' => false,

    'fields' => [
      "product_id" => [
        'type' => self::TYPE_INT,
        'size' => 11,
        'validate' => 'isunsignedInt',
        'required' => true
      ],

      "username" => [
        'type' => self::TYPE_STRING,
        'size' => 255,
        'validate' => 'isCleanHtml',
        'required' => true
      ],

      "comment" => [
        'type' => self::TYPE_STRING,
        'size' => 255,
        'validate' => 'isCleanHtml',
        'required' => true
      ],

      "visible" => [
        'type' => self::TYPE_BOOL,
      ]
    ]
  ];
}
