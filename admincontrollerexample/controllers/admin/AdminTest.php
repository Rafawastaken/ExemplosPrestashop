<?php

// importar modelo database
require_once(_PS_MODULE_DIR_ . 'admincontrollerexample/classes/commentclass.php');

class AdminTestController extends ModuleAdminController
{
  public function __construct()
  {
    $this->table = 'testcomment'; // Nome da tabela
    $this->className = "CommentClass"; // Nome da Classe
    $this->identifier = CommentClass::$definition['primary']; // Identificador ID

    $this->bootstrap = true;

    $this->fields_list = [
      'id' => [
        "title" => 'ID',
        'align' => "center"
      ],

      "user_id" => [
        "title" => "User ID",
        "algin" => "left"
      ],

      "comment" => [
        "title" => "Comment",
        "algin" => "left"
      ]
    ];

    $this->addRowAction('View');
    $this->addRowAction('Edit');
    $this->addRowAction('Delete');

    parent::__construct();
  }

  public function renderForm()
  {
    $this->fields_form = [
      'legend' => [
        "title" => "Add New Comment",
        "icon" => "icon-cog",
      ],

      'input' => [
        [
          "type" => "text",
          "label" => "ID",
          "name" => "user_id",
          "class" => "input fixed-with-sm",
          "required" => true,
          "empty_message" => "Enter the User ID"
        ],
        [
          "type" => "text",
          "label" => "Comment",
          "name" => "comment",
          "class" => "input fixed-with-sm",
          "required" => true,
          "empty_message" => "Enter Comment"
        ]
      ],
      "submit" => [
        "type" => "submit",
        "title" => "Add Comment",
        "name" => "submitComment"
      ]
    ];

    return parent::renderForm();
  }

  public function renderView()
  {
    $tplFile = 'module:admincontrollerexample/views/templates/admin/view.tpl';
    $tpl = $this->context->smarty->createTemplate($tplFile);

    // fetch data
    $sql = new DbQuery();
    $sql->select('*')
      ->from($this->table)
      ->where("id = " . Tools::getValue('id'));

    // Execute query
    $data = Db::getInstance()->executeS($sql);

    // assing vars
    $tpl->assign([
      "data" => $data[0]
    ]);

    return $tpl->fetch();
  }
}
