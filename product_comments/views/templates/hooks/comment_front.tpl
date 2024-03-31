<div class="tabs">
  <h4 class="display-3">Comentários Produto</h4>

  <div class="panel-comments">
    {foreach $comments as $c}
      {if $c.visible == 1}
        <ul class="comment-container">
          <li class="username">{$c.username}</li>
          <li class="comment">{$c.comment}</li>
        </ul>
      {/if}
    {/foreach}
  </div>
</div>

<div class="tabs">
  <h4 class="display-3">Escreva Comentario</h4>
  <p class="text-muted">Comentario Sujeito a Aprovação</p>

  <form action="{$smarty.server.REQUEST_URI}" method="post" id="form-comentario">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" class="form-control" placeholder="Username" required />
    </div>

    <div class="form-group">
      <label for="comment">Comentario</label>
      <textarea name="comment" cols="30" rows="5" class="form-control" placeholder="Seu comentario" required></textarea>
    </div>
    <input type="hidden" name="product_id" value="{$id_product}">
    <input type="submit" value="Adicionar" name="addComment" class="btn btn-primary" id="btnSubmitComment">
  </form>



</div>