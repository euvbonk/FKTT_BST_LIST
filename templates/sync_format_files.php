
      <h3>Synchronisiert Formatierungs Dateien (DTD, XSL, CSS):</h3>
      <h4><span style="font-weight:bold;text-decoration:underline;">Achtung:</span> Aktion ersetzt rigoros die ausgew&auml;hlte(n) Datei(en)</h4>
      <p>
        Folgende Dateien stehen f&uuml;r die Synchronisierung zur Auswahl:
        <form action="<?php $this->printValue('FormActionUri'); ?>" method="post">
            <ul style="list-style-type:none;"><?php $this->printValue('ListEntries'); ?></ul>
            <input type="hidden" name="cmd" value="sync" />
            <input type="submit" value="Synchronisierung starten" />
        </form>
      </p>
      <p><?php $this->printValue('CmdMessages'); ?></p>
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php $this->printValue('LastChange'); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a>
      </p>
