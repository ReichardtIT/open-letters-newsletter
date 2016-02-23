<?php
/**
 * Diese Datei enthaelt den View, der das Formular zur Anmeldung zum Newsletter
 * erstellt.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Frontend
 */
?>
<h2>i18n(newsletter_unsubscribe_form_headline)</h2>
<?php
echo("<form method=\"get\" action=\"" . $_SERVER['PHP_SELF'] . "?sendit\">\n");
?>

<section>
    <p class="bg-warning lead">i18n(newsletter_unsubscribe_form_text)</p>
</section>
<div class="row">
<div class="col-md-8">
  <div class="form-group">
    <label for="unsubscribe" class="control-label">E-Mail Adresse</label>
    <input type="text" class="form-control" name="unsubscribe" />
  </div>
</div>
</div>
<div class="row">
<div class="col-md-4">
    <button type="submit" class="btn btn-default btn-lg" name="button">i18n(form_unsubscribe_submit)</button>
</div>
</div>
</form>
