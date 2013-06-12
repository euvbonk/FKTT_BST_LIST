
      <h3>Addon Daten Verzeichnis als Zip sichern:</h3>
      <p>
        Folgende Archive sind vorhanden und werden
        <span style="text-decoration:underline;font-weight:bold;font-style: italic;">vor der Sicherung entfernt!</span>:
        <ul><?php $this->printFunc('getZipList'); ?></ul>
        <form action="<?php $this->printFunc('getFormActionUri'); ?>" method="post">
            <input type="hidden" name="cmd" value="export" />
            <input type="submit" value="Sicherung starten" />
        </form>
      </p>
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php $this->getLastChangeTimestamp(); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a>
      </p>
