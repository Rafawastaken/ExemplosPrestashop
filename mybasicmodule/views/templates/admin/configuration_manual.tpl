{if $form_subm}
  {if isset($message)}
    <div class="alert alert-success">
      <p class="alert-text">Valor atualizado com sucesso</p>
    </div>
  {else}
    <div class="alert alert-danger">
      <p class="alert-text">Ocorreu um erro</p>
    </div>
  {/if}
{/if}


<div class="panel">
  <h2>Configurações My Basic Module</h2>
  <form action="" method="post">
    <div class="form-group">
      <label for="input1" class="form-control-label">Normal Input</label>
      <input value="{$valor_input}" type="text" name="nome_input" id="input1" class="form-control" placeholder="Input 1"
        required>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-6">
          <a href="#" onclick="{$backUrl};" class="btn btn-secondary form-control">Voltar</a>
        </div>
        <div class="col-md-6">
          <input name="submit_form" type="submit" value="Guardar" class="btn btn-primary form-control">
        </div>
      </div>
    </div>
  </form>
</div>