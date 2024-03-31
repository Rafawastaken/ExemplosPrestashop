{if !empty($message)}
  <div class="alert alert-success" role="alert">
    <p class="alert-text">
      {$message}
    </p>
  </div>
{/if}

<div class="panel">
  <h2 class="display-4">Comentários Produtos</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Product ID</th>
        <th>Username</th>
        <th>Comment</th>
        <th>Visible</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>

      {foreach $comments as $c}
        <tr>
          <td>{$c.id}</td>
          <td>{$c.product_id}</td>
          <td>{$c.username}</td>
          <td>{$c.comment}</td>
          <td>
            {if $c.visible == 0}
              <i class="material-icons action-disabled">clear</i>
            {else}
              <i class="material-icons action-enabled">check</i>
            {/if}
          </td>
          <td celspan="2">
            <form action="#" method="post">
              {if $c.visible == 0}
                <input type="submit" value="Show" name="toggleEntry" class="btn btn-primary form-control">
              {else}
                <input type="submit" value="Hide" name="toggleEntry" class="btn btn-primary form-control">
              {/if}
              <input type="hidden" name="product_id" value='{$c.id}' />
              <input type="hidden" name="visible" value='{$c.visible}' />
            </form>

            <form action="#" method="post">
              <input type="submit" value="DELETE" name="deleteEntry" class="btn btn-danger form-control">
              <input type="hidden" name="product_id" value='{$c.id}' />
              <input type="hidden" name="visible" value='{$c.visible}' />
            </form>
          </td>
        </tr>
      {/foreach}
    </tbody>
  </table>
</div>