<?php

require_once(_PS_MODULE_DIR_ . 'product_comments/classes/CommentClass.php');

class ProductCommentsController extends ModuleAdminController
{
  public function __construct()
  {
    $this->table = 'productcomments';
    $this->className = "CommentClass"; // Nome da Classe
    $this->identifier = CommentClass::$definition['primary']; // Identificador ID
    $this->bootstrap = true;

    parent::__construct();
  }

  public function initContent()
  {
    parent::initContent();
    $message = "";

    if (Tools::isSubmit('toggleEntry')) {
      $edit_comment_id = Tools::getValue('product_id');
      $edit_comment_vis = Tools::getValue('visible');

      echo $edit_comment_vis . ' ' . $edit_comment_id;

      if ((int)$edit_comment_vis === 1) {
        $edit_comment_vis = 0;
      } else {
        $edit_comment_vis = 1;
      }

      $sql_update = "UPDATE psdev_productcomments SET visible =" . $edit_comment_vis . " WHERE id = " . $edit_comment_id;
      Db::getInstance()->execute($sql_update);

      if ($edit_comment_vis == 0) {
        $message = "Comment: #" . $edit_comment_id . " disabled!";
      } else {
        $message = "Comment: #" . $edit_comment_id . " visible!";
      }
    }

    if (Tools::isSubmit('deleteEntry')) {
      $edit_comment_id = Tools::getValue('product_id');
      $sql_delete = 'DELETE FROM psdev_productcomments WHERE id = ' . $edit_comment_id;
      Db::getInstance()->execute($sql_delete);

      $message = "Comment: #" . $edit_comment_id . " deleted!";
    }


    $sql = "SELECT * FROM psdev_productcomments ORDER BY id DESC";
    $result = Db::getInstance()->ExecuteS($sql);

    $comments = array();
    foreach ($result as $row) {
      $comments[] = array(
        "id" => $row['id'],
        "product_id" => $row['product_id'],
        "username" => $row['username'],
        "comment" => $row['comment'],
        "visible" => $row['visible'],
      );
    }
    $this->context->smarty->assign([
      "comments" => $comments,
      "message" => $message
    ]);

    $content = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'product_comments/views/templates/admin/admincomments.tpl');
    $this->context->smarty->assign(array(
      'content' => $this->content . $content,
    ));
  }
}
