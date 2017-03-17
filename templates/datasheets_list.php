      <?php
          $js = "$('select.datasheet-lang-select').change(function(){var tref=$(this).val();if(tref.length>0 && tref!='#'){parent.top.location.href='".$this->SheetViewUrl()."'+tref;}});";
          global $page;
          $page->jQueryCode .= $js;
      ?>
      <h3>Die Betriebstellen des FKTT und ihre Daten</h3>
      <form action="<?php $this->printValue('FormActionUri'); ?>" method="post" style="padding-bottom: 1%;">
          <table style="margin-bottom: 1%;"><tr><td style="text-align: right;">Ordnen nach:&nbsp;</td><td><label><select name="order" size="1"><?php $this->printValue('OrderOptionsUI'); ?></select></label></td></tr><tr><td style="text-align: right;">Epoche:&nbsp;</td><td><label><select name="epoch" size="1" style="width:45px;"><?php $this->printValue('EpochOptionsUI'); ?></select></label></td></tr><tr><td style="text-align: right;"><input type="submit" name="startFilter" value="Start"></td><td style="text-align: right;"><input type="submit" value="Filter l&ouml;schen" name="reset"></td></tr></table>
          <!-- BSTLIST_ENTRY_FOR_FKTT_PORTAL --><?php $this->printValue('Table'); ?><script type="text/javascript">if(typeof jQuery!='undefined'){jQuery(document).ready(function($){<?php echo $js;?>});}</script><!-- BSTLIST_FLUSH_FOR_FKTT_PORTAL -->
      </form>
      <p><?php $this->printValue('YellowPageLink'); ?></p>
      <p>Achtung! Die Betriebsstellendateien sind XML-Dateien und nur mit folgenden Browsern ansehbar: Internet Explorer ab Version 5, Opera ab Version 5.12, Netscape ab Version 6, Mozilla Firefox ab Version 1.0</p>
      <p>Datenblatt anlegen oder bearbeiten: <?php $this->printValue('ApplicationUrl'); ?> Editor</p>
      <p>F&uuml;r die Zukunft ist geplant, die Seiten auch als HTML bzw. XHTML abzulegen und die Fahrplaner und BFO Ersteller werden das ganze auch noch im pdf Format bekommen.</p>
      <p><?php $this->printValue('CSVListLink'); ?></p>
      <p><?php $this->printValue('ZipBundleLink'); ?></p>
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php $this->printValue('LastChange'); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a></p>
