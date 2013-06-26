
      <h1>Die Betriebstellen des FKTT und ihre Daten</h1>
      <form action="<?php $this->printValue('FormActionUri'); ?>" method="post">
          <table border="0"><tr><td style="text-align: right;">Ordnen nach:&nbsp;</td><td><select name="order" size="1"><?php $this->printValue('OrderOptionsUI'); ?></select></td></tr><tr><td style="text-align: right;">Epoche:&nbsp;</td><td><select name="epoch" size="1" style="width:45px;"><?php $this->printValue('EpochOptionsUI'); ?></select></td></tr><tr><td style="text-align: right;"><input type="submit" name="startFilter" value="Start" /></td><td style="text-align: right;"><input type="submit" value="Filter l&ouml;schen" name="reset" /></td></tr></table>
          <!-- BSTLIST_ENTRY_FOR_FKTT_PORTAL --><?php $this->printValue('Table'); ?><!-- BSTLIST_FLUSH_FOR_FKTT_PORTAL -->
      </form>
      <p><?php $this->printValue('YellowPageLink'); ?></p>
      <p>Achtung! Die Betriebsstellendateien sind XML-Dateien<br/>und nur mit folgenden Browsern ansehbar:<br />
         Internet Explorer ab Version 5, Opera ab Version 5.12,<br/>Netscape ab Version 6, Mozilla Firefox ab Version 1.0</p>
      <p>Neues Datenblatt anlegen: <?php $this->printValue('ApplicationUrl'); ?> Editor</p>
      <p>F&uuml;r die Zukunft ist geplant, die Seiten auch als HTML<br/>bzw. XHTML abzulegen und die Fahrplaner und BFO<br/>
         Ersteller werden das ganze auch noch im pdf Format<br/> bekommen.</p>
      <p><?php $this->printValue('CSVListLink'); ?></p>
      <p><?php $this->printValue('ZipBundleLink'); ?></p>
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php $this->printValue('LastChange'); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a></p>
