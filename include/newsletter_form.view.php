<?php
/**
 * Diese Datei enthaelt den View, der das Formular zur Anmeldung zum Newsletter
 * erstellt.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Frontend
 */
?>
<h2>i18n(newsletter_subscribe_form_headline)</h2>
<?php
echo("<form method=\"post\" action=\"" . filter_input(INPUT_SERVER, 'PHP_SELF') . "?sendit\">\n");
?>
<form class="form-horizontal">
    <?php
    if (isset($params['error'])) {
        echo("<tr>\n");
        echo("<td colspan=\"2\"><font color='#ff0000'>" . $params['error'] . "</font></td>\n");
        echo("</tr>\n");
    }
    ?>
<section>
  <div class="form-group">
    <p class="bg-warning lead">i18n(form_star_description)</p>
  </div>
</section>
<section>
  <div class="form-group">
    <label for="gender" class="control-label">i18n(form_word_salutation)</label>
    <select name="title" id="gender" class="form-control">
      <option value="">i18n(form_please_select)</option>
	  <option value="Frau" <?php if ($params['title'] == "Frau") echo "selected"; ?> >i18n(form_word_misses)</option>
	  <option value="Herr" <?php if ($params['title'] == "Herr") echo "selected"; ?> >i18n(form_word_mister)</option>
    </select>
  </div>
</section>
<section>
  <div class="form-group">
    <label for="firstname" class="control-label">i18n(form_word_firstname)*</label>
    <input type="text" class="form-control" name="firstname"
      <?php
        if (isset($params['firstname']))
        echo "value=\"" . $params['firstname'] . "\" />";
        else
        echo "value=\"\" />"
      ?>
  </div>
</section>
<section>
  <div class="form-group">
    <label for="name" class="control-label">i18n(form_word_lastname)*</label>
    <input type="text" class="form-control" name="name"
      <?php
        if (isset($params['name']))
        echo "value=\"" . $params['name'] . "\" />";
        else
        echo "value=\"\" />"
      ?>
  </div>
</section>
<section>
  <div class="form-group">
    <label for="newsemail" class="control-label">i18n(form_word_mailaddress)*</label>
    <input type="email" class="form-control" name="newsemail" 
      <?php
        if (isset($params['newsemail']))
        echo "value=\"" . $params['newsemail'] . "\" />";
        else
        echo "value=\"\" />"
      ?>
  </div>
</section>
<section>
  <div class="form-group">
    <input type="checkbox" name="newsagreement" value="newsletter_notice_accepted" class="news"> * i18n(form_newsagreement)
  </div>
</section>
<button type="submit" class="btn btn-default btn-lg">i18n(form_subscribe_submit)</button>
</form>
